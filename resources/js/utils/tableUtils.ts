import { router } from '@inertiajs/vue3';

export const defaultSorting = (routeName: string, field: string, direction: string) => {
    router.get(route(routeName), { sort: field, direction }, { preserveState: true, preserveScroll: true });
};

export const onPageChange = (routeName: string, event: any) => {
    // Obtém os parâmetros atuais da URL
    const params = new URLSearchParams(window.location.search);
    const currentParams = Object.fromEntries(params.entries());

    // Mescla os parâmetros atuais com os novos parâmetros de paginação
    const mergedParams = {
        ...currentParams,
        page: event.page + 1,
        per_page: event.rows,
    };

    router.get(route(routeName), mergedParams, { preserveState: true, preserveScroll: true });
};

export const onSortChange = (routeName: string, event: any) => {
    const params = new URLSearchParams(window.location.search);
    const currentParams = Object.fromEntries(params.entries());

    let mergedParams = {
        ...currentParams,
    };

    if (!event.sortField || event.sortOrder === 0) {
        delete mergedParams.sort;
        delete mergedParams.direction;
    } else {
        mergedParams = {
            ...mergedParams,
            sort: event.sortField,
            direction: event.sortOrder === 1 ? 'asc' : 'desc',
        };
    }

    router.get(route(routeName), mergedParams, { preserveState: true, preserveScroll: true });
};

export const onFilterChange = (routeName: string, filters = {}) => {
    // Obtém os parâmetros atuais da URL
    const params = new URLSearchParams(window.location.search);
    const currentParams = Object.fromEntries(params.entries());

    // Mescla os parâmetros atuais com os novos filtros
    const mergedParams = {
        ...currentParams,
        ...filters,
    };

    // Remove chaves com valores vazios
    const cleanedParams = Object.fromEntries(Object.entries(mergedParams).filter(([key, value]) => value != null && value !== ''));

    // Realiza a navegação com Inertia
    router.get(route(routeName), cleanedParams, { preserveState: true, preserveScroll: true });
};
