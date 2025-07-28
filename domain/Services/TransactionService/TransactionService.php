<?php

namespace domain\Services\TransactionService;

use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\TransactionBalance;

class TransactionService
{
    protected $transaction;

    private $transaction_balance;

    private $currency;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->transaction = new Transaction;
        $this->transaction_balance = new TransactionBalance;
        $this->currency = new Currency;
    }

    public function store($data)
    {
        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $data['type'] = 5;
        $data['description'] = 'Manual Entry';
        $this->transaction->create($data);

        $transaction_balance = $this->transaction_balance->where('currency_id', $data['currency_id'])->first();
        if ($transaction_balance) {
            if ($data['sign'] == 0) {
                $transaction_balance->amount = $transaction_balance->amount - $data['amount'];
            } elseif ($data['sign'] == 1) {
                $transaction_balance->amount = $transaction_balance->amount + $data['amount'];
            }
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($data['currency_id']);
            $balance_data['currency_id'] = $data['currency_id'];
            $balance_data['code'] = $currency['code'];
            if ($data['sign'] == 0) {
                $balance_data['amount'] = -$data['amount'];
            } elseif ($data['sign'] == 1) {
                $balance_data['amount'] = $data['amount'];
            }
            $this->transaction_balance->create($balance_data);
        }
    }

    public function edit($transaction_id)
    {
        return $this->transaction->find($transaction_id);
    }

    public function update($data, $transaction_id)
    {
        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $transaction = $this->transaction->find($transaction_id);

        $business_detail = BusinessDetail::first();
        if ($data['sign'] == 0) {
            $business_detail->transaction_balance = $business_detail->transaction_balance - ($data['amount'] - $transaction->amount);
        } elseif ($data['sign'] == 1) {
            $business_detail->transaction_balance = $business_detail->transaction_balance + ($data['amount'] - $transaction->amount);
        }
        $business_detail->save();

        $transaction_balance = $this->transaction_balance->where('currency_id', $data['currency_id'])->first();
        if ($transaction_balance) {
            $previous_transaction_balance = $this->transaction_balance->where('currency_id', $transaction['currency_id'])->first();
            if ($previous_transaction_balance->currency_id == $data['currency_id']) {
                if ($data['sign'] == 0) {
                    $transaction_balance->amount = $transaction_balance->amount - ($data['amount'] - $transaction->amount);
                } elseif ($data['sign'] == 1) {
                    $transaction_balance->amount = $transaction_balance->amount + ($data['amount'] - $transaction->amount);
                }
                $transaction_balance->save();
            } else {
                if ($transaction['sign'] == 0) {
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + $transaction->amount;
                    $transaction_balance->amount = $transaction_balance->amount - $data['amount'];
                } elseif ($transaction['sign'] == 1) {
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount - $transaction->amount;
                    $transaction_balance->amount = $transaction_balance->amount + $data['amount'];
                }
                $previous_transaction_balance->save();
                $transaction_balance->save();
            }
        } else {
            $previous_transaction_balance = $this->transaction_balance->where('currency_id', $transaction['currency_id'])->first();
            if ($previous_transaction_balance->currency_id != $data['currency_id']) {
                if ($transaction['sign'] == 0) {
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + $transaction->amount;
                } elseif ($transaction['sign'] == 1) {
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount - $transaction->amount;
                }
                $previous_transaction_balance->save();
            }
            $currency = $this->currency->find($data['currency_id']);
            $balance_data['currency_id'] = $data['currency_id'];
            $balance_data['code'] = $currency['code'];
            if ($data['sign'] == 0) {
                $balance_data['amount'] = -$data['amount'];
            } elseif ($data['sign'] == 1) {
                $balance_data['amount'] = $data['amount'];
            }
            $this->transaction_balance->create($balance_data);
        }

        return $transaction->update($data);
    }

    public function delete($transaction_id)
    {
        $transaction = $this->transaction->find($transaction_id);

        $business_detail = BusinessDetail::first();
        if ($transaction->sign == 0) {
            $business_detail->transaction_balance = $business_detail->transaction_balance + $transaction->amount;
        } elseif ($transaction->sign == 1) {
            $business_detail->transaction_balance = $business_detail->transaction_balance - $transaction->amount;
        }
        $business_detail->save();
        $transaction_balance = $this->transaction_balance->where('currency_id', $transaction->currency_id)->first();
        if ($transaction_balance) {
            if ($transaction->sign == 0) {
                $transaction_balance->amount = $transaction_balance->amount + $transaction->amount;
            } elseif ($transaction->sign == 1) {
                $transaction_balance->amount = $transaction_balance->amount - $transaction->amount;
            }
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction->currency_id);
            $balance_data['currency_id'] = $transaction->currency_id;
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = $transaction->amount;
            if ($transaction->sign == 0) {
                $balance_data['amount'] = $transaction->amount;
            } elseif ($transaction->sign == 1) {
                $balance_data['amount'] = -$transaction->amount;
            }
            $this->transaction_balance->create($balance_data);
        }

        return $transaction->delete();
    }

    public function calculateTotals($transactions)
    {
        // Initialize totals array
        $totals = [
            'total' => 0,
        ];

        // Calculate totals
        foreach ($transactions as $transaction) {
            // Calculate total
            $totals['total'] += $transaction['amount'];
        }

        return $totals;
    }
}
