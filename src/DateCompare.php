<?php

namespace NikitaKurdin\DateCompare;

/*
Возможные форматы дат:

   01:00:05 21.07.2017 (известная полная дата со временем)
   01:05 21.07.2017 (секунды неизвестны)
   01: 21.07.2017 (неизвестны минуты)
   21.07.2017 (только дата)
   07.2017 (известен месяц и год)
   2017 (известен год)
   01: (известен час)
   01:05 (известен час и минуты)
   01:05:17 (известно полностью время)
*/

class DateCompare
{ 
    public $date_separate = ".";
    
    /**
     * [ConvertFormate метод определяет формат даты и переводит ёё в секунды]
     * @param string $data [Дата различного формата]
     */
    public function ConvertFormate ($data)
    {
        $data = trim($data);
        $seconds = 0;

        $data_arr = explode(" ", $data); // Разделяем время и дату

        $time = 0;
        $date = 0;

        // Проверяем является ли первый элемент массива - временем
        if (preg_match("#:#", $data_arr[0]) OR strlen($data_arr[0]) == 2)
        {
            $time = $this->ConvertTime($data_arr[0]);

            if (isset($data_arr[1]))
            {
                $date = $this->ConvertDate($data_arr[1]);
            }
        }
        else
        {
            $date = $this->ConvertDate($data_arr[0]);
        }

        $seconds = $time+$date;

        return $seconds;
    }

    /**
     * [ConvertTime Метод расчитывает секунды из времени]
     * @param string $data [Время]
     */
    public function ConvertTime($data)
    {
        $seconds = 0;
        $data_arr = explode(":", $data);

        $hour = 0;
        $minute = 0;
        $second = 0;

        $hour = $data_arr[0]*60*60;

        if (isset($data_arr[1]) AND $data_arr[1] != "")
        {
            $minute = $data_arr[1]*60;
            //$minute -= 1;
        }

        if (isset($data_arr[2]) AND $data_arr[2] != "")
        {
            $second = $data_arr[2];
        }

        $seconds = $hour+$minute+$second;

        return $seconds;
    }

    /**
     * [ConvertDate Метод расчитывает секунды из даты]
     * @param string $data [Дата]
     */
    public function ConvertDate($data)
    {
        $seconds = 0;
        $data_arr = explode($this->date_separate, $data);

        // Переменные для хранения секунд
        $day = 0;
        $month = 0;
        $year = 0;

        // Переменные для хранения числа дней, месяцев или годов
        $day_tmp = 0;
        $month_tmp = 0;
        $year_tmp = 0;

        // Если дата содержит все 3 параметра
        if (count($data_arr) == 3)
        {
            $seconds = strtotime($data);

            return $seconds;
        }
        // Если в дате 2 параметра
        elseif (count($data_arr) == 2)
        {
            foreach ($data_arr as $key => $val)
            {
                // в массиве есть год, то есть формат был YYYY/MM или MM/YYYY
                if (strlen($val) == 4)
                {
                    $year_tmp = $val;
                    unset($data_arr[$key]); // Удаляем этот ключ из массива, чтобы получить значение другого элемента
                    $month_tmp = array_pop($data_arr); // Получаем месяц, так как, если был год, то другое значение - месяц
                }
            }

            // Если в массиве не было года, значит формат массива DD/MM
            if (!$year_tmp)
            {
                $day_tmp = $data_arr[0];
                $month_tmp = $data_arr[1];
            }
        }
        // Если в дате только год
        else
        {
            $year_tmp = $data_arr[0];
        }


        if ($day_tmp)
        {
            $day_tmp -= 1; // Чтобы при умножении не получили +1 день
            $day = $day_tmp*86400; // Получаем кол-во дней в секундах
        }

        if ($month_tmp)
        {
            // Получаем год, чтобы можно было определить кол-во дней в месяцах до указанного
            if ($year_tmp)
            {
                $year_month = $year_tmp;
            }
            else
            {
                $year_month = date("Y");
            }

            $month_tmp = intval($month_tmp); // Убераем возможность наличия нуля в начале
            if ($month_tmp < 10)
            {
                $month_tmp = "0".$month_tmp;
            }

            $month = strtotime("01.{$month_tmp}.{$year_month}");
        }

        if ($year_tmp AND !$month)
        {
            $year = strtotime("01.01.{$year_tmp}"); // Получаем кол-во дней в секундах
        }
        elseif ($year_tmp AND $month)
        {
            $year = 0;
        }

        $seconds = $day+$month+$year;

        return $seconds;
    }

    /**
     * [Check сверяем первую дату со второй]
     * @param string $date1 [Дата 1]
     * @param string $date2 [Дата 2]
     */
    public function Check($date1, $date2)
    {
        $compare1 = $this->ConvertFormate($date1);
        $compare2 = $this->ConvertFormate($date2);

        if ($compare1 > $compare2)
        {
            return 1;
        }
        elseif ($compare1 < $compare2)
        {
            return -1;
        }
        else
        {
            return 0;
        }
    }
}
