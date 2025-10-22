<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(auth()->user()->isVisitor())
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sistema de Rangos</h3>
                        <div class="bg-gradient-to-r from-yellow-100 to-orange-100 p-4 rounded-lg mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">{{ auth()->user()->getRankIcon() }}</span>
                                    <div>
                                        <h4 class="text-xl font-bold text-gray-900">{{ auth()->user()->getRankDisplayName() }}</h4>
                                        <p class="text-gray-600">{{ auth()->user()->points }} puntos</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Próximo rango:</p>
                                    @if(auth()->user()->rank === 'bronze')
                                        <p class="font-semibold">🥈 Plata (1 punto)</p>
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min(100, (auth()->user()->points / 1) * 100) }}%"></div>
                                        </div>
                                    @elseif(auth()->user()->rank === 'silver')
                                        <p class="font-semibold">🥇 Oro (5 puntos)</p>
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-gray-400 h-2 rounded-full" style="width: {{ min(100, ((auth()->user()->points - 1) / 4) * 100) }}%"></div>
                                        </div>
                                    @else
                                        <p class="font-semibold text-green-600">¡Rango máximo!</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h5 class="font-semibold text-blue-900 mb-2">Cómo ganar puntos:</h5>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Comentar festividades: 2 puntos</li>
                                <li>• Votar por festividades: 10 puntos</li>
                                <li>• Login diario: 1 punto</li>
                                <li>• Visitar festividades de otras localidades: 1 punto/día</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
