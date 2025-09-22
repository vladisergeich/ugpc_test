<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OperatorDataResource extends ResourceCollection
{
    public function toArray($request)
    {
        dd($this->resource);
        // Группируем данные по пользователю
        $groupedData = [];

        foreach ($this->resource as $operation) {
            // Получаем ID пользователя (или другое поле для группировки)
            $userId = $operation->user_id;
            
            // Если пользователя ещё нет в массиве, создаем его запись
            if (!isset($groupedData[$userId])) {
                $groupedData[$userId] = [
                    'user' => $operation->user->employer_name_1c, // Имя пользователя
                    'totalShaft' => 0,
                    'totalWorkTime' => 0,
                    'operations' => [],
                ];
            }

            // Вычисляем время работы для каждой операции
            $workTime = $this->calculateWorkTime($operation->posting_date, $operation->start_time, $operation->end_time);

            // Добавляем данные по операции
            $operationDescription = $operation->operation->description;
            if (!isset($groupedData[$userId]['operations'][$operationDescription])) {
                $groupedData[$userId]['operations'][$operationDescription] = [
                    'description' => $operationDescription,
                    'qtyShaft' => 0,
                    'workTime' => 0,
                    'avgTimePerUnit' => 0,
                    'usefulTime' => 0,
                ];
            }

            // Агрегируем данные по операции
            $groupedData[$userId]['operations'][$operationDescription]['qtyShaft']++;
            $groupedData[$userId]['operations'][$operationDescription]['workTime'] += $workTime;
            $groupedData[$userId]['totalShaft']++;
            $groupedData[$userId]['totalWorkTime'] += $workTime;

            // Рассчитываем среднее время на единицу и полезное время
            $groupedData[$userId]['operations'][$operationDescription]['avgTimePerUnit'] =
                round($groupedData[$userId]['operations'][$operationDescription]['workTime'] / $groupedData[$userId]['operations'][$operationDescription]['qtyShaft']);
            
            $groupedData[$userId]['operations'][$operationDescription]['usefulTime'] =
                round(($groupedData[$userId]['operations'][$operationDescription]['workTime'] / 720) * 100); // Пример расчета
        }

        // Выводим данные через dd() для отладки
        dd($groupedData);  // Это отобразит структуру данных в браузере

        // Возвращаем новые данные в нужной структуре
        return array_values($groupedData);
    }

    // Пример расчета времени работы
    private function calculateWorkTime($startDate, $startTime, $endDate, $endTime)
    {
        $startDateTime = \Carbon\Carbon::parse("$startDate $startTime");
        $endDateTime = \Carbon\Carbon::parse("$endDate $endTime");

        if ($endDateTime->lt($startDateTime)) {
            $endDateTime->addDay();
        }

        return $startDateTime->diffInMinutes($endDateTime);
    }
}
