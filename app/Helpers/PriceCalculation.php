<?php

namespace App\Helpers;

use App\InvoiceItems;

class PriceCalculation
{


    public function calculatePrice($price, $tax_type, $tax_amount, $discount_amount, $discount_type, $trans_fee, $quantity = 1)
    {
        $discount = 0;
        $tax = 0;
        $trans_amount = 0;
        $amount = $quantity * $price;
        if ($amount > 0) {

            if ($discount_type == 1) {
                $discount = $discount_amount * 0.01 * $amount;
            } elseif ($discount_type == 2) {
                $discount = $discount_amount;
            }
            if ($tax_type == 2) {
                $tax = $tax_amount;
            } else {
                $tax = $tax_amount * 0.01 * $amount;
            }
            $trans_amount = $trans_fee * 0.01 * $amount;
            $price = $amount + $tax + $trans_amount - $discount;
        }

        return [
            'discount' => $discount,
            'tax' => $tax,
            'trans_amount' => $trans_amount,
            'price' => ceil($price),
            'amount' => $amount,
        ];
    }

    public function invoicePriceCalculation($client, $item)
    {
        $price = $item['plan']->price;
        $tax_type = $item['plan']->govt_charges_type;
        $tax_amount = $item['plan']->govt_charges_amt;
        $discount_amount = $item['plan']->discount_amount;
        $discount_type = $item['plan']->discount_type;
        $trans_fee = $item['plan']->transaction_fee;
        $apply_discount = $item['plan']->apply_discount;
        $quantity = $item['quantity'] ?? 1;
        $discount = 0;
        $tax = 0;
        $trans_amount = 0;
        $amount = $quantity * $price;
        $model = class_basename($item['plan']);

        if ($amount > 0) {
            if ($discount_type == 1) {
                $disc = $discount_amount * 0.01 * $amount;
            } elseif ($discount_type == 2) {
                $disc = $discount_amount;
            } else {
                $disc = $discount;
            }

            $check_model = InvoiceItems::where("model", $model)
                ->whereHas("invoice", function ($q) use ($client) {
                    $q->where('status', 'Paid')
                        ->where("cl_id", $client->id);
                });
            $check_model_id = $check_model->where('model_id', $item['plan']->id);
            if ($apply_discount == 1) {
                if ($check_model->count() == 0) {
                    $discount = $disc;
                } else {
                    $discount = 0;
                }
            } else if ($apply_discount == 3) {
                if ($check_model_id->count() == 0) {
                    $discount = $disc;
                } else {
                    $discount = 0;
                }
            } else {
                $discount = $disc;
            }


            if ($tax_type == 2) {
                $tax = $tax_amount;
            } else {
                $tax = $tax_amount * 0.01 * $amount;
            }
            $trans_amount = $trans_fee * 0.01 * $amount;
            $price = $amount + $tax + $trans_amount - $discount;
        }

        return [
            'discount' => $discount,
            'tax' => $tax,
            'trans_amount' => $trans_amount,
            'price' => ceil($price),
            'amount' => $amount,
        ];
    }
}
