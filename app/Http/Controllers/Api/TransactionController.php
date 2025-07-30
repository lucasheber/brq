<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Repositories\Transaction\TransactionRepository;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\HeaderParameter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    /**
     * TransactionController constructor.
     */
     #[HeaderParameter('Authorization', 'Bearer token for authentication', type: 'string', example:' Bearer your_token_here')]
    public function __construct(
        private readonly TransactionRepository $transactionRepository
    ) {
    }

    /**
     * List all transactions
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'status'     => 'nullable|string|in:' . implode(',', array_column(\App\Enums\TransactionStatus::cases(), 'value')),
        ]);

        return response()->json(['data' => $this->transactionRepository->filter($validated)]);
    }

    /**
     * Store a new transaction
     */
    #[BodyParameter('amount', 'The amount of the transaction', type: 'number', example: 100)]
    #[BodyParameter('currency', 'The currency of the transaction', type: 'string', example: 'USD')]
    #[BodyParameter('description', 'The description of the transaction', type: 'string', example: 'Transaction description')]
    #[BodyParameter('status', 'The status of the transaction', type: 'string', example: 'pending')]
    public function store(\App\Http\Requests\StoreTransactionRequest $request)
    {
        $transaction = $this->transactionRepository->create($request->validated());

        return response()->json(['data' => $transaction], Response::HTTP_CREATED);
    }

    /**
     * Show a specific transaction
     *
     * @param int $id The ID of the transaction
     */
    public function show(int $id, Request $request)
    {
        $transaction = $this->transactionRepository->find($id);

        if (! $transaction instanceof Transaction) {
            return response()->json(['message' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $transaction]);
    }

    /**
     * Update a specific transaction
     *
     * @param int $id The ID of the transaction
     */
    public function update(int $id, \App\Http\Requests\StoreTransactionRequest $request)
    {
        $transaction = $this->transactionRepository->find($id);

        if (! $transaction instanceof Transaction) {
            return response()->json(['message' => 'Transaction not found'], Response::HTTP_NOT_FOUND);
        }

        $transaction = $this->transactionRepository->update($id, $request->validated());

        return response()->json(['data' => $transaction]);
    }

    /**
     * Delete a specific transaction
     *
     * @param int $id The ID of the transaction
     */
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
