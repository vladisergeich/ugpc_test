<?php

namespace App\Services;

use App\Models\OperationLedgerEntry;
use Carbon\Carbon;

class StatisticService
{
    /**
     * Метод для агрегации статистики по операторам.
     * 
     * @param $operations
     * @return array
     */
    public function aggregateOperatorsData($operations)
    {
        $groupedData = $operations->groupBy('user_id');

        $result = [];

        foreach ($groupedData as $userId => $userOperations) {
            $userData = [
                'user_id' => $userId,
                'user_name' => $userOperations->first()->user->employer_name_1c,
                'operations' => [],
                'totalShaft' => 0,
                'absenceTime' => 0,
            ];

            // Группируем по операциям
            $operationsByDescription = $userOperations->groupBy('operation.description');
            
            foreach ($operationsByDescription as $operationDescription => $userOps) {
                $workTime = $userOps->sum(function ($operation) {
                    return $this->calculateWorkTime($operation->start_time, $operation->end_time);
                });

                $uniqueShiftCodes = $userOps->pluck('work_shift_code')->unique()->count();
                $totalShiftTime = $uniqueShiftCodes * 720; // Общее время смен в минутах
                $usefulTime = ($workTime / $totalShiftTime) * 100;

                $qtyShaft = $userOps->count();
                $avgTimePerUnit = $workTime / $qtyShaft;

                // Добавляем операцию в структуру данных
                $userData['operations'][$operationDescription] = [
                    'operation_description' => $operationDescription,
                    'workTime' => $workTime,
                    'qtyShaft' => $qtyShaft,
                    'avgTimePerUnit' => round($avgTimePerUnit),
                    'usefulTime' => round($usefulTime), 
                ];

                // Обновляем общее количество валов и время отсутствия работы
                $userData['totalShaft'] += $qtyShaft;
                $userData['absenceTime'] += $this->calculateAbsenceTime($userOps);
            }

            $result[] = $userData;
        }

        usort($result, function ($a, $b) {
            return $b['totalShaft'] - $a['totalShaft']; // Сортировка по убыванию totalShaft
        });

        return $result;
    }

    public function aggregateShiftsData($operations)
    {
        $groupedData = $operations->groupBy('letter');

        $result = [];

        foreach ($groupedData as $letter => $shiftOperations) {
            $shiftData = [
                'letter' => $letter,
                'operations' => [],
                'absenceTime' => 0,
                'workTimeAll' => 0,
            ];

            // Группируем по операциям
            $operationsByDescription = $shiftOperations->groupBy('operation.description');
            
            foreach ($operationsByDescription as $operationDescription => $shiftOps) {
                $workTime = $shiftOps->sum(function ($operation) {
                    return $this->calculateWorkTime($operation->start_time, $operation->end_time);
                });

                $uniqueShiftCodes = $shiftOps->pluck('work_shift_code')->unique()->count();
                $totalShiftTime = $uniqueShiftCodes * 720; // Общее время смен в минутах
                $usefulTime = ($workTime / $totalShiftTime) * 100;

                $qtyShaft = $shiftOps->count();
                $avgTimePerUnit = $workTime / $qtyShaft;

                // Добавляем операцию в структуру данных
                $shiftData['operations'][$operationDescription] = [
                    'operation_description' => $operationDescription,
                    'workTime' => $workTime,
                    'avgTimePerUnit' => round($avgTimePerUnit),
                    'usefulTime' => round($usefulTime), 
                ];

                // Обновляем общее количество валов и время отсутствия работы
                $shiftData['workTimeAll'] += $workTime;
                $shiftData['absenceTime'] += $this->calculateAbsenceTime($shiftOps);
            }

            $result[] = $shiftData;
        }

        return $result;
    }

    // Метод для расчета рабочего времени
    private function calculateWorkTime($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        if ($end->lt($start)) {
            $end->addDay();
        }
        
        return $start->diffInMinutes($end);
    }

    // Метод для расчета времени отсутствия работы
    private function calculateAbsenceTime($operations)
    {
        $totalPlannedTime = $operations->pluck('work_shift_code')->unique()->count() * 720; // Пример: 720 минут в смене
        $totalWorkTime = $operations->sum(function ($operation) {
            return $this->calculateWorkTime($operation->start_time, $operation->end_time);
        });

        return $totalPlannedTime - $totalWorkTime;
    }
}