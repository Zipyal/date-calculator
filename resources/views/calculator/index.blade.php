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

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- переключатель режима -->
                <div class="flex gap-2 mb-6 bg-gray-100 p-1 rounded-lg">
                    <button 
                        type="button"
                        onclick="switchMode('between')"
                        id="tab-between"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-purple-700 shadow"
                    >
                        <i class="fas fa-exchange-alt mr-1"></i>
                        Между датами
                    </button>
                    <button 
                        type="button"
                        onclick="switchMode('add')"
                        id="tab-add"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600 hover:text-gray-800"
                    >
                        <i class="fas fa-plus mr-1"></i>
                        Прибавить к дате
                    </button>
                </div>

                <!-- ФОРМА 1: между датами -->
                <form 
                    id="form-between"
                    action="{{ route('calculator.calculate') }}" 
                    method="POST" 
                    class="space-y-6 {{ ($activeMode ?? 'between') === 'between' ? '' : 'hidden' }}"
                >
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play-circle text-purple-500 mr-1"></i>
                            Начальная дата
                        </label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ old('start_date', $selectedStartDate ?? date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('start_date') border-red-500 @enderror"
                        >
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag-checkered text-purple-500 mr-1"></i>
                            Конечная дата
                        </label>
                        <input 
                            type="date" 
                            name="end_date" 
                            value="{{ old('end_date', $selectedEndDate ?? '') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('end_date') border-red-500 @enderror"
                        >
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-600 transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-magic mr-2"></i>
                        Рассчитать разницу
                    </button>
                </form>

                <!-- ФОРМА 2: прибавить к дате -->
                <form 
                    id="form-add"
                    action="{{ route('calculator.add') }}" 
                    method="POST" 
                    class="space-y-6 {{ ($activeMode ?? 'between') === 'add' ? '' : 'hidden' }}"
                >
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day text-purple-500 mr-1"></i>
                            Начальная дата
                        </label>
                        <input 
                            type="date" 
                            name="base_date" 
                            value="{{ old('base_date', $selectedStartDate ?? date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('base_date') border-red-500 @enderror"
                        >
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
                            <input 
                                type="number" 
                                name="amount" 
                                value="{{ old('amount', 30) }}"
                                min="1"
                                max="10000"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('amount') border-red-500 @enderror"
                            >
                            @error('amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-ruler text-purple-500 mr-1"></i>
                                Единица
                            </label>
                            <select 
                                name="unit"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition-colors @error('unit') border-red-500 @enderror"
                            >
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
                    
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-600 transition-all transform hover:scale-105"
                    >
                        <i class="fas fa-plus-circle mr-2"></i>
                        Прибавить
                    </button>
                </form>

                <!-- быстрые кнопки -->
                <div class="mt-6 space-y-2">
                    <p class="text-sm text-gray-600 font-medium">Быстрый выбор:</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setQuickDate('today', 'tomorrow')" class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Завтра
                        </button>
                        <button type="button" onclick="setQuickDate('today', 'week')" class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Через неделю
                        </button>
                        <button type="button" onclick="setQuickDate('today', 'month')" class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Через месяц
                        </button>
                        <button type="button" onclick="setQuickDate('today', 'year')" class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors font-medium">
                            Через год
                        </button>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl shadow-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                    Результаты
                </h2>

                @if(isset($calculation))
                    <div class="space-y-4">
                        @if(isset($addResult))
                            <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-600">К дате</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ $addResult['base_date'] }} + {{ $addResult['amount'] }} {{ $addResult['unit_label'] }}
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
                                <span class="text-gray-600"><i class="fas fa-hourglass text-green-500 mr-2"></i>Секунд:</span>
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
                    </div>
                @else
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
                        <form action="{{ route('calculator.clearHistory') }}" method="POST" onsubmit="return confirm('Удалить всю историю?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors font-medium">
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

    <script>
        function switchMode(mode) {
            const tabBetween = document.getElementById('tab-between');
            const tabAdd = document.getElementById('tab-add');
            const formBetween = document.getElementById('form-between');
            const formAdd = document.getElementById('form-add');

            if (mode === 'between') {
                formBetween.classList.remove('hidden');
                formAdd.classList.add('hidden');

                tabBetween.classList.add('bg-white', 'text-purple-700', 'shadow');
                tabBetween.classList.remove('text-gray-600');

                tabAdd.classList.remove('bg-white', 'text-purple-700', 'shadow');
                tabAdd.classList.add('text-gray-600');
            } else {
                formAdd.classList.remove('hidden');
                formBetween.classList.add('hidden');

                tabAdd.classList.add('bg-white', 'text-purple-700', 'shadow');
                tabAdd.classList.remove('text-gray-600');

                tabBetween.classList.remove('bg-white', 'text-purple-700', 'shadow');
                tabBetween.classList.add('text-gray-600');
            }
        }

        function setQuickDate(startType, endType) {
            const today = new Date();
            const formatDate = (date) => date.toISOString().split('T')[0];
            
            let endDate = new Date(today);
            
            switch(endType) {
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
            
            document.querySelector('input[name="start_date"]').value = formatDate(today);
            document.querySelector('input[name="end_date"]').value = formatDate(endDate);
        }
    </script>
</body>
</html>