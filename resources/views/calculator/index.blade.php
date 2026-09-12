<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор дат</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Калькулятор дат</h1>
        
        <form action="{{ route('calculator.calculate') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
            @csrf
            <div class="mb-4">
                <label>Начальная дата</label>
                <input type="date" name="start_date" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label>Конечная дата</label>
                <input type="date" name="end_date" class="border p-2 w-full" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Рассчитать
            </button>
        </form>
        
        @if(isset($calculation))
            <div class="mt-6 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Результаты:</h2>
                <p>Дней: {{ $calculation['total_days'] }}</p>
                <p>Недель: {{ $calculation['weeks'] }}</p>
                <p>Часов: {{ number_format($calculation['hours']) }}</p>
            </div>
        @endif
    </div>
</body>
</html>