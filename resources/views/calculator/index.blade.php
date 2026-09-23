<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор дат - DateCalc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
    </style>
</head>

<body class="p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <!-- шапка -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white rounded-full px-6 py-2 shadow-lg mb-4">
                <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                <span class="font-semibold text-gray-800">DateCalc</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                Калькулятор дат
            </h1>
            <p class="text-purple-100 text-lg">
                Узнайте точную разницу между двумя датами
            </p>
        </div>

        <!-- форма результаты -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="glass-effect rounded-2xl shadow-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-calculator text-purple-600 mr-2"></i>
                    Введите даты
                </h2>

                <!-- Виджет: сколько осталось -->
                @if(isset($remaining))
                    <div class="grid grid-cols-3 gap-2 mb-6">
                        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $remaining['days_left_in_year'] }}</div>
                            <div class="text-xs text-gray-600 mt-1">дней до конца года</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $remaining['days_left_in_month'] }}</div>
                            <div class="text-xs text-gray-600 mt-1">дней до конца месяца</div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $remaining['days_left_in_week'] }}</div>
                            <div class="text-xs text-gray-600 mt-1">дней до конца недели</div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- переключатель режима -->
                <div class="flex gap-2 mb-6 bg-gray-100 p-1 rounded-lg">
                    <button type="button" onclick="switchMode('between')" id="tab-between"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-purple-700 shadow">
                        <i class="fas fa-exchange-alt mr-1"></i>
                        Между датами
                    </button>
                    <button type="button" onclick="switchMode('add')" id="tab-add"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600 hover:text-gray-800">
                        <i class="fas fa-plus mr-1"></i>
                        Прибавить
                    </button>
                    <button type="button" onclick="switchMode('subtract')" id="tab-subtract"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600 hover:text-gray-800">
                        <i class="fas fa-minus mr-1"></i>
                        Отнять
                    </button>
                    <button type="button" onclick="switchMode('time')" id="tab-time"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600 hover:text-gray-800 whitespace-nowrap">
                        <i class="fas fa-clock mr-1"></i>
                        Времена
                    </button>
                </div>

                <!-- ФОРМА 1: между датами -->
                <form id="form-between" action="{{ route('calculator.calculate') }}" method="POST"
                    class="space-y-6 {{ ($activeMode ?? 'between') === 'between' ? '' : 'hidden' }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play-circle text-purple-500 mr-1"></i>
                            Начальная дата
                        </label>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', $selectedStartDate ?? date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag-checkered text-purple-500 mr-1"></i>
                            Конечная дата
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date', $selectedEndDate ?? '') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-600 transition-all transform hover:scale-105">
                        <i class="fas fa-magic mr-2"></i>
                        Рассчитать разницу
                    </button>
                </form>

                <!-- ФОРМА 2: прибавить к дате -->
                <form id="form-add" action="{{ route('calculator.add') }}" method="POST"
                    class="space-y-6 {{ ($activeMode ?? 'between') === 'add' ? '' : 'hidden' }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day text-purple-500 mr-1"></i>
                            Начальная дата
                        </label>
                        <input type="date" name="base_date"
                            value="{{ old('base_date', $selectedStartDate ?? date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('base_date') border-red-500 @enderror">
                        @error('base_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag text-purple-500 mr-1"></i>
                                Сколько
                            </label>
                            <input type="number" name="amount" value="{{ old('amount', 30) }}" min="1" max="10000"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('amount') border-red-500 @enderror">
                            @error('amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-ruler text-purple-500 mr-1"></i>
                                Единица
                            </label>
                            <select name="unit"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('unit') border-red-500 @enderror">
                                <option value="days" {{ old('unit') === 'days' ? 'selected' : '' }}>Дней</option>
                                <option value="weeks" {{ old('unit') === 'weeks' ? 'selected' : '' }}>Недель</option>
                                <option value="months" {{ old('unit') === 'months' ? 'selected' : '' }}>Месяцев</option>
                                <option value="years" {{ old('unit') === 'years' ? 'selected' : '' }}>Лет</option>
                            </select>
                            @error('unit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-600 transition-all transform hover:scale-105">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Прибавить
                    </button>
                </form>

                <!-- ФОРМА 3: отнять от даты -->
                <form id="form-subtract" action="{{ route('calculator.subtract') }}" method="POST"
                    class="space-y-6 {{ ($activeMode ?? 'between') === 'subtract' ? '' : 'hidden' }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day text-purple-500 mr-1"></i>
                            Начальная дата
                        </label>
                        <input type="date" name="base_date"
                            value="{{ old('base_date', $selectedStartDate ?? date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('base_date') border-red-500 @enderror">
                        @error('base_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag text-purple-500 mr-1"></i>
                                Сколько
                            </label>
                            <input type="number" name="amount" value="{{ old('amount', 30) }}" min="1" max="10000"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('amount') border-red-500 @enderror">
                            @error('amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-ruler text-purple-500 mr-1"></i>
                                Единица
                            </label>
                            <select name="unit"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('unit') border-red-500 @enderror">
                                <option value="days" {{ old('unit') === 'days' ? 'selected' : '' }}>Дней</option>
                                <option value="weeks" {{ old('unit') === 'weeks' ? 'selected' : '' }}>Недель</option>
                                <option value="months" {{ old('unit') === 'months' ? 'selected' : '' }}>Месяцев</option>
                                <option value="years" {{ old('unit') === 'years' ? 'selected' : '' }}>Лет</option>
                            </select>
                            @error('unit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-600 transition-all transform hover:scale-105">
                        <i class="fas fa-minus-circle mr-2"></i>
                        Отнять
                    </button>
                </form>

                <!-- ФОРМА 4: разница между временами -->
                <form id="form-time" action="{{ route('calculator.time') }}" method="POST"
                    class="space-y-6 {{ ($activeMode ?? 'between') === 'time' ? '' : 'hidden' }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock text-purple-500 mr-1"></i>
                            Начальное время
                        </label>
                        <input type="time" name="start_time" value="{{ old('start_time', '14:30') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clock text-purple-500 mr-1"></i>
                            Конечное время
                        </label>
                        <input type="time" name="end_time" value="{{ old('end_time', '19:45') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-600 transition-all transform hover:scale-105">
                        <i class="fas fa-clock mr-2"></i>
                        Рассчитать разницу
                    </button>
                </form>

                <!-- быстрые кнопки -->
                <div class="mt-6 space-y-2">
                    <p class="text-sm text-gray-600 font-medium">Быстрый выбор:</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setQuickDate('today', 'tomorrow')"
                            class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Завтра
                        </button>
                        <button type="button" onclick="setQuickDate('today', 'week')"
                            class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Через неделю
                        </button>
                        <button type="button" onclick="setQuickDate('today', 'month')"
                            class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Через месяц
                        </button>
                        <button type="button" onclick="setQuickDate('today', 'year')"
                            class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Через год
                        </button>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl shadow-2xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                        Результаты
                    </h2>
                    @if(isset($calculation))
                        <button type="button" onclick="copyResult()" id="copy-btn"
                            class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            <i class="fas fa-copy mr-1"></i>
                            Скопировать
                        </button>
                    @endif
                </div>


                @if(isset($timeResult))
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-600">Период</p>
                            <p class="text-lg font-bold text-gray-800">
                                {{ $timeResult['start_time'] }} → {{ $timeResult['end_time'] }}
                            </p>
                        </div>

                        <div class="stat-card rounded-lg p-6 text-center card-hover">
                            <div class="text-4xl font-bold text-purple-600">{{ $timeResult['formatted'] }}</div>
                            <div class="text-sm text-gray-600 mt-1">Разница</div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Минут:</span>
                                <span class="font-semibold">{{ number_format($timeResult['total_minutes']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Секунд:</span>
                                <span class="font-semibold">{{ number_format($timeResult['total_seconds']) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($calculation))
                    <!-- скрытый текст для копирования -->
                    <div id="copy-text" class="hidden">
                        Результат: {{ $calculation['start_date_formatted'] }} → {{ $calculation['end_date_formatted'] }}
                        Всего дней: {{ $calculation['total_days'] }}
                        Недель: {{ $calculation['weeks'] }}
                        Месяцев: {{ $calculation['months'] }}
                        Лет: {{ $calculation['years'] }}
                        Часов: {{ $calculation['hours'] }}
                        Минут: {{ $calculation['minutes'] }}
                        Секунд: {{ $calculation['seconds'] }}
                        Рабочих дней: {{ $calculation['weekdays'] }}
                        Выходных: {{ $calculation['weekends'] }}
                    </div>

                    <div class="space-y-4">
                        @if(isset($addResult))
                            <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-600">
                                    {{ ($activeMode ?? '') === 'subtract' ? 'От даты' : 'К дате' }}
                                </p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ $addResult['base_date'] }} {{ ($activeMode ?? '') === 'subtract' ? '−' : '+' }}
                                    {{ $addResult['amount'] }} {{ $addResult['unit_label'] }}
                                </p>
                                <p class="text-2xl font-bold text-purple-700 mt-2">
                                    = {{ $addResult['result_date'] }}
                                </p>
                            </div>
                        @else
                            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-600">Период расчета</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ $calculation['start_date_formatted'] }} → {{ $calculation['end_date_formatted'] }}
                                </p>
                            </div>
                            @if(isset($humanDiff))
                                <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-3 text-center">
                                    <p class="text-sm text-gray-600">Это примерно</p>
                                    <p class="text-xl font-bold text-purple-700">{{ $humanDiff }}</p>
                                </div>
                            @endif
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div class="stat-card rounded-lg p-4 text-center card-hover">
                                <div class="text-3xl font-bold text-purple-600">{{ $calculation['total_days'] }}</div>
                                <div class="text-sm text-gray-600">Всего дней</div>
                            </div>
                            <div class="stat-card rounded-lg p-4 text-center card-hover">
                                <div class="text-3xl font-bold text-blue-600">{{ $calculation['weeks'] }}</div>
                                <div class="text-sm text-gray-600">Недель</div>
                            </div>
                            <div class="stat-card rounded-lg p-4 text-center card-hover">
                                <div class="text-3xl font-bold text-green-600">{{ $calculation['months'] }}</div>
                                <div class="text-sm text-gray-600">Месяцев</div>
                            </div>
                            <div class="stat-card rounded-lg p-4 text-center card-hover">
                                <div class="text-3xl font-bold text-orange-600">{{ $calculation['years'] }}</div>
                                <div class="text-sm text-gray-600">Лет</div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600"><i class="fas fa-clock text-purple-500 mr-2"></i>Часов:</span>
                                <span class="font-semibold">{{ number_format($calculation['hours']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600"><i class="fas fa-stopwatch text-blue-500 mr-2"></i>Минут:</span>
                                <span class="font-semibold">{{ number_format($calculation['minutes']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600"><i
                                        class="fas fa-hourglass text-green-500 mr-2"></i>Секунд:</span>
                                <span class="font-semibold">{{ number_format($calculation['seconds']) }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $calculation['weekdays'] }}</div>
                                <div class="text-sm text-gray-600">Рабочих дней</div>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $calculation['weekends'] }}</div>
                                <div class="text-sm text-gray-600">Выходных</div>
                            </div>
                        </div>

                        {{-- Блок ISO-неделей и кварталом --}}

                        @if(isset($dateInfo))
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-info-circle text-indigo-500 mr-1"></i>
                                    О конечной дате
                                </p>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">ISO-неделя:</span>
                                        <span class="font-semibold">{{ $dateInfo['iso_week'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Квартал:</span>
                                        <span class="font-semibold">Q{{ $dateInfo['quarter'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">День в году:</span>
                                        <span class="font-semibold">{{ $dateInfo['day_of_year'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Високосный:</span>
                                        <span class="font-semibold">{{ $dateInfo['is_leap_year'] ? 'Да' : 'Нет' }}</span>
                                    </div>
                                    <div class="flex justify-between col-span-2">
                                        <span class="text-gray-600">День недели:</span>
                                        <span class="font-semibold">{{ $dateInfo['day_of_week'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif(!isset($timeResult))
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Введите даты и нажмите "Рассчитать"</p>
                        <p class="text-gray-400 text-sm mt-2">Результаты появятся здесь</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- история -->
        @if(isset($recentCalculations) && $recentCalculations->count() > 0)
            <div class="mt-8 glass-effect rounded-2xl shadow-2xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-history text-purple-600 mr-2"></i>
                        Последние расчеты
                    </h3>
                    <form action="{{ route('calculator.clearHistory') }}" method="POST" id="clear-history-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="openClearModal()"
                            class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors font-medium">
                            <i class="fas fa-trash mr-1"></i>
                            Очистить
                        </button>
                    </form>
                </div>
                <div class="space-y-2">
                    @foreach($recentCalculations as $calc)
                        <div class="flex justify-between items-center bg-gray-50 rounded-lg px-4 py-2">
                            <span class="text-gray-700">
                                {{ $calc->start_date->format('d.m.Y') }} → {{ $calc->end_date->format('d.m.Y') }}
                            </span>
                            <span class="font-semibold text-purple-600">
                                {{ $calc->days_difference }} дней
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- футер -->
        <div class="text-center mt-8 text-purple-200">
            <p>© {{ date('Y') }} DateCalc - Калькулятор дат на Laravel</p>
        </div>
    </div>

    <!-- модалка подтверждения очистки истории -->
    <div id="clear-modal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-200">
        <!-- затемнение фона -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeClearModal()"></div>

        <!-- содержимое модалки -->
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    Очистить историю?
                </h3>
                <p class="text-gray-600 mb-6">
                    Все записи будут удалены безвозвратно. Это действие нельзя отменить.
                </p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeClearModal()"
                        class="flex-1 px-4 py-2 border-2 border-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        Отмена
                    </button>
                    <button type="button" onclick="submitClearForm()"
                        class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                        <i class="fas fa-trash mr-1"></i>
                        Удалить
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchMode(mode) {
            const tabs = {
                between: document.getElementById('tab-between'),
                add: document.getElementById('tab-add'),
                subtract: document.getElementById('tab-subtract'),
                time: document.getElementById('tab-time'),
            };

            const forms = {
                between: document.getElementById('form-between'),
                add: document.getElementById('form-add'),
                subtract: document.getElementById('form-subtract'),
                time: document.getElementById('form-time'),
            };

            for (const key in tabs) {
                if (key === mode) {
                    tabs[key].classList.add('bg-white', 'text-purple-700', 'shadow');
                    tabs[key].classList.remove('text-gray-600');
                    forms[key].classList.remove('hidden');
                } else {
                    tabs[key].classList.remove('bg-white', 'text-purple-700', 'shadow');
                    tabs[key].classList.add('text-gray-600');
                    forms[key].classList.add('hidden');
                }
            }
        }

        function setQuickDate(startType, endType) {
            const today = new Date();
            const formatDate = (date) => date.toISOString().split('T')[0];

            const activeTab = document.querySelector('[id^="tab-"].bg-white');
            const mode = activeTab ? activeTab.id.replace('tab-', '') : 'between';

            if (mode === 'between') {
                const endDate = new Date(today);

                switch (endType) {
                    case 'tomorrow':
                        endDate.setDate(endDate.getDate() + 1);
                        break;
                    case 'week':
                        endDate.setDate(endDate.getDate() + 7);
                        break;
                    case 'month':
                        endDate.setMonth(endDate.getMonth() + 1);
                        break;
                    case 'year':
                        endDate.setFullYear(endDate.getFullYear() + 1);
                        break;
                }

                document.querySelector('#form-between input[name="start_date"]').value = formatDate(today);
                document.querySelector('#form-between input[name="end_date"]').value = formatDate(endDate);

            } else {
                const formId = mode === 'add' ? 'form-add' : 'form-subtract';
                const form = document.getElementById(formId);

                form.querySelector('input[name="base_date"]').value = formatDate(today);

                switch (endType) {
                    case 'tomorrow':
                        form.querySelector('input[name="amount"]').value = 1;
                        form.querySelector('select[name="unit"]').value = 'days';
                        break;
                    case 'week':
                        form.querySelector('input[name="amount"]').value = 7;
                        form.querySelector('select[name="unit"]').value = 'days';
                        break;
                    case 'month':
                        form.querySelector('input[name="amount"]').value = 1;
                        form.querySelector('select[name="unit"]').value = 'months';
                        break;
                    case 'year':
                        form.querySelector('input[name="amount"]').value = 1;
                        form.querySelector('select[name="unit"]').value = 'years';
                        break;
                }
            }
        }

        function copyResult() {
            const text = document.getElementById('copy-text').innerText;
            const btn = document.getElementById('copy-btn');

            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check mr-1"></i>Скопировано';

                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                }, 1500);
            });
        }

        function openClearModal() {
            const modal = document.getElementById('clear-modal');
            modal.classList.remove('hidden');
        }

        function closeClearModal() {
            document.getElementById('clear-modal').classList.add('hidden');
        }

        function submitClearForm() {
            document.getElementById('clear-history-form').submit();
        }
    </script>
</body>

</html>