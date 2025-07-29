<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Repositories\Transaction\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    /**
     * TransactionController constructor.
     */
    public function __construct(
        private readonly TransactionRepository $transactionRepository
    ) {
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'status'     => 'nullable|string|in:' . implode(',', array_column(\App\Enums\TransactionStatus::cases(), 'value')),
        ]);

        return response()->json(['data' => $this->transactionRepository->filter($validated)]);
    }

    public function store(\App\Http\Requests\StoreTransactionRequest $request)
    {
        $transaction = $this->transactionRepository->create($request->validated());

        return response()->json(['data' => $transaction], Response::HTTP_CREATED);
    }

    public function show(int $id, Request $request)
    {
        $transaction = $this->transactionRepository->find($id);

        if (! $transaction instanceof Transaction) {
            return response()->json(['message' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $transaction]);
    }

    public function update(int $id, \App\Http\Requests\StoreTransactionRequest $request)
    {
        $transaction = $this->transactionRepository->find($id);

        if (! $transaction instanceof Transaction) {
            return response()->json(['message' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        $transaction = $this->transactionRepository->update($id, $request->validated());

        return response()->json(['data' => $transaction]);
    }

    public function destroy(int $id)
    {
        $transaction = $this->transactionRepository->find($id);

        if (! $transaction instanceof Transaction) {
            return response()->json(['message' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        $transaction->delete();

        return response()->noContent();
    }
}
