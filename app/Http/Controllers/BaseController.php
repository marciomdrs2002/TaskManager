<?php

namespace App\Http\Controllers;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

abstract class BaseController extends Controller
{
    protected $repository;
    protected $resourceClass;
    protected $modelName;
    protected $viewPrefix;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
        $this->modelName = $this->getModelName();
        $this->resourceClass = $this->getResourceClass();
        $this->viewPrefix = $this->getViewPrefix();
    }

    public function index(Request $request)
    {
        $items = $this->repository->findWithFilters($request);

        if ($this->resourceClass) {
            $items = $this->resourceClass::collection($items);
        }

        return Inertia::render($this->viewPrefix . '/Index', [
            'items' => $items
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $item = $this->repository->create($validated);

        return redirect()->back();
    }

    public function show($id)
    {
        $item = $this->repository->findById($id);

        if ($this->resourceClass) {
            $item = new $this->resourceClass($item);
        }

        return Inertia::render($this->viewPrefix . '/Show', [
            'item' => $item
        ]);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->back();
    }
    
    public function update(Request $request, $id) {
        $validated = $request->validate($this->rules());
        $item = $this->repository->update($id, $validated);

        return redirect()->back();
    }

    // Métodos abstratos que devem ser implementados pelos controllers concretos
    abstract protected function getModelName(): string;
    abstract protected function getResourceClass(): string;
    abstract protected function getViewPrefix(): string;
    abstract protected function rules(): array;


    /**
     * Obtém a definição do formulário a partir de um arquivo PHP
     * 
     * @param string|null $customForm Nome personalizado para o arquivo de formulário
     * @return array
     */
    protected function getFormDefinition(?string $customForm = null): array
    {
        // Determinar o caminho do arquivo
        $formName = $customForm ?? $this->modelName;
        $formPath = app_path("Http/Forms/{$formName}.php");

        if (!File::exists($formPath)) {
            return $this->getDefaultFormDefinition();
        }

        // Incluir o arquivo PHP que retorna um array
        $formDefinition = require($formPath);

        // Permite que os controllers processem a definição (carregar dados dinâmicos)
        return $this->processFormDefinition($formDefinition);
    }

    protected function processFormDefinition(array $formDefinition): array
    {
        // Obtém o modelo a partir do repositório
        $modelClass = get_class($this->repository->getModel());

        // Processa cada campo do formulário
        foreach ($formDefinition['fields'] as $key => $field) {
            // Se o campo tiver opções dinâmicas definidas
            if (isset($field['dynamicOptions'])) {
                $methodName = $field['dynamicOptions'];

                // Verifica se o método existe na classe do modelo
                if (method_exists($modelClass, $methodName)) {
                    // Chama o método estático do modelo
                    $formDefinition['fields'][$key]['options'] = $modelClass::$methodName();
                    // Remove o identificador de opções dinâmicas
                    unset($formDefinition['fields'][$key]['dynamicOptions']);
                }
            }
        }

        return $formDefinition;
    }

    /**
     * Obter definição de formulário padrão quando o arquivo não existe
     * 
     * @return array
     */
    protected function getDefaultFormDefinition(): array
    {
        return [
            'fields' => [],
            'actions' => [
                [
                    'label' => 'Salvar',
                    'type' => 'submit',
                    'color' => 'primary',
                ],
                [
                    'label' => 'Cancelar',
                    'type' => 'button',
                    'action' => 'close',
                    'color' => 'secondary',
                ],
            ],
        ];
    }

    /**
     * Rota para obter definição de formulário para o modelo atual
     * 
     * @param Request $request
     * @param int|null $id ID do item a ser editado (opcional)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModelFormDefinition(Request $request, $id = null)
    {
        $data = [
            'formDefinition' => $this->getFormDefinition()
        ];

        if ($id) {
            $item = $this->repository->findById($id);
            if ($this->resourceClass) {
                $item = new $this->resourceClass($item);
            }
            $data['item'] = $item;
        }

        return response()->json($data);
    }
}
