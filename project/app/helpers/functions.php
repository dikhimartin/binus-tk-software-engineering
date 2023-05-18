<?php
    use Carbon\Carbon;

    function arrStatusActive(){
        return array('Y' => __('main.active'), 'N' => __('main.non-active'));
    }

    function arrStatusActiveLabel(){
        return array('Y' => 'info', 'N' => 'danger');
    }

    function arrGender() {
        return array('male' => __('main.male'), 'female' => __('main.female'));
    }

    function arrStatusTransactionlabel() {
        return array('pending' => 'info', 'reject' => 'danger', 'finish' => 'success');
    }

    function arrStatusTransaction() {
        return array('pending' => __('main.pending'), 'reject' => __('main.reject'), 'finish' => __('main.finish'));
    }

    function convertDateTime($date){
        // Convert date string to Carbon object
        $convertedDate = Carbon::createFromFormat('m/d/Y, h:i A', $date);

        // Format the date as database-friendly datetime
        $formattedDate = $convertedDate->format('Y-m-d H:i:s');

        return $formattedDate;
    }

    function convertDate($date){
        // Convert date string to Carbon object
        $convertedDate = Carbon::createFromFormat('m/d/Y', $date);

        // Format the date as database-friendly datetime
        $formattedDate = $convertedDate->format('Y-m-d');

        return $formattedDate;
    }

    function calculateDaysBetweenTwoDateTime($startDate, $endDate){
        // Convert date strings to Carbon objects
        $start = Carbon::createFromFormat('m/d/Y, h:i A', $startDate);
        $end = Carbon::createFromFormat('m/d/Y, h:i A', $endDate);

        // Calculate the number of days between the two dates
        $numberOfDays = $end->diffInDays($start);

        return $numberOfDays;
    }

    function calculateDaysBetweenTwoDate($startDate, $endDate){
        // Convert date strings to Carbon objects
        $start = Carbon::createFromFormat('m/d/Y', $startDate);
        $end = Carbon::createFromFormat('m/d/Y', $endDate);

        // Calculate the number of days between the two dates
        $numberOfDays = $end->diffInDays($start);

        return $numberOfDays;
    }