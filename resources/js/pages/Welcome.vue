<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col bg-white dark:bg-gray-900">
        <!-- Header -->
        <header class="flex w-full items-center justify-between border-b border-gray-100 p-4 dark:border-gray-800">
            <div class="flex items-center space-x-2">
                <AppLogo class="h-8 w-8" />
            </div>

            <nav>
                <div class="flex items-center space-x-4">
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="route('dashboard')"
                            class="rounded-lg bg-gray-900 px-5 py-2 font-medium text-white transition-colors hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white"
                        >
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-lg border border-gray-300 px-5 py-2 text-gray-700 transition-colors hover:border-gray-400 dark:border-gray-700 dark:text-gray-200 dark:hover:border-gray-600"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="rounded-lg bg-gray-900 px-5 py-2 font-medium text-white transition-colors hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="mx-auto flex max-w-7xl flex-1 flex-col items-center justify-center gap-12 p-6 lg:flex-row">
            <div class="space-y-6 lg:w-1/2">
                <h2 class="text-4xl font-bold leading-tight text-gray-900 dark:text-white md:text-5xl">
                    Organize suas tarefas, <span class="text-gray-700 dark:text-gray-300">simplifique sua vida</span>
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Uma plataforma simples e intuitiva para gerenciar suas tarefas diárias, projetos e colaborações. Aumente sua produtividade e nunca
                    mais esqueça um prazo importante.
                </p>
                <div class="flex flex-wrap gap-4">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('register')"
                        class="rounded-lg bg-gray-900 px-8 py-3 text-lg font-medium text-white transition-colors hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white"
                    >
                        Começar agora
                    </Link>
                    <Link
                        :href="$page.props.auth.user ? route('dashboard') : route('login')"
                        class="rounded-lg border border-gray-900 px-8 py-3 text-lg font-medium text-gray-900 transition-colors hover:bg-gray-100 dark:border-gray-100 dark:text-gray-100 dark:hover:bg-gray-800"
                    >
                        {{ $page.props.auth.user ? 'Ir para Dashboard' : 'Fazer login' }}
                    </Link>
                </div>
            </div>

            <div class="relative flex items-center justify-center lg:w-1/2">
                <img
                    src="/images/hero2.png"
                    alt="TaskManager Dashboard"
                    class="h-auto max-w-full rounded-lg border border-gray-100 object-cover shadow-lg dark:border-gray-700"
                />
                <!-- Adiciona um efeito sutil de brilho atrás da imagem -->
                <div class="absolute -inset-1 -z-10 rounded-xl bg-gray-100 opacity-30 blur-xl dark:bg-gray-800"></div>
            </div>
        </main>

        <!-- Features Section -->
        <section class="bg-gray-50 py-12 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-6">
                <h2 class="mb-12 text-center text-3xl font-bold text-gray-900 dark:text-white">Recursos que simplificam seu dia</h2>

                <div class="grid gap-8 md:grid-cols-3">
                    <div
                        class="rounded-xl border border-gray-100 bg-white p-6 transition-shadow hover:shadow-lg dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                            <svg class="h-6 w-6 text-gray-700 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Organização simples</h3>
                        <p class="text-gray-600 dark:text-gray-400">Crie, organize e priorize suas tarefas com facilidade.</p>
                    </div>

                    <div
                        class="rounded-xl border border-gray-100 bg-white p-6 transition-shadow hover:shadow-lg dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                            <svg class="h-6 w-6 text-gray-700 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Lembretes e prazos</h3>
                        <p class="text-gray-600 dark:text-gray-400">Nunca mais perca um prazo com nosso sistema de lembretes.</p>
                    </div>

                    <div
                        class="rounded-xl border border-gray-100 bg-white p-6 transition-shadow hover:shadow-lg dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                            <svg class="h-6 w-6 text-gray-700 dark:text-gray-300" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M17 20H7M18 14C18 15.3807 16.8807 16.5 15.5 16.5C14.1193 16.5 13 15.3807 13 14M16 14C16 11.7909 14.2091 10 12 10C9.79086 10 8 11.7909 8 14M12 14C12 15.3807 10.8807 16.5 9.5 16.5C8.11929 16.5 7 15.3807 7 14M20 14C20 17.866 16.4183 21 12 21C7.58172 21 4 17.866 4 14C4 10.134 7.58172 7 12 7C16.4183 7 20 10.134 20 14Z"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M12 7V5C12 3.89543 12.8954 3 14 3H17C18.1046 3 19 3.89543 19 5V7.5"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Colaboração em equipe</h3>
                        <p class="text-gray-600 dark:text-gray-400">Compartilhe tarefas e projetos com sua equipe facilmente.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gray-100 bg-white py-8 dark:border-gray-800 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-6 text-center">
                <p class="text-gray-600 dark:text-gray-400">&copy; {{ new Date().getFullYear() }} TaskManager - Simplifique sua produtividade</p>
            </div>
        </footer>
    </div>
</template>
