<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\RescueRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Adoption Requests
    |--------------------------------------------------------------------------
    */

    public function updateAdoptionRequest(
        Request $request,
        int $id
    ) {
        $adoptionRequest = AdoptionRequest::with('post')
            ->findOrFail($id);

        // فقط صاحب بوست التبني يقدر يقرر
        if ($adoptionRequest->post->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to manage this request.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:APPROVED,REJECTED',
        ]);

        // ما نقدر نعدل طلب محسوم
        if ($adoptionRequest->status !== 'PENDING') {
            return response()->json([
                'message' => 'This request has already been processed.'
            ], 409);
        }

        $adoptionRequest->update([
            'status' => $validated['status'],
        ]);

        // إذا تمت الموافقة، نقفل البوست
        if ($validated['status'] === 'APPROVED') {

            $adoptionRequest->post->update([
                'status' => 'CLOSED',
            ]);

            // نرفض باقي الطلبات المعلقة
            AdoptionRequest::where('post_id', $adoptionRequest->post_id)
                ->where('id', '!=', $adoptionRequest->id)
                ->where('status', 'PENDING')
                ->update([
                    'status' => 'REJECTED',
                ]);
        }

        return response()->json([
            'message' => $validated['status'] === 'APPROVED'
                ? 'Adoption request approved successfully.'
                : 'Adoption request rejected successfully.',

            'request' => [
                'id' => $adoptionRequest->id,
                'post_id' => $adoptionRequest->post_id,
                'status' => $adoptionRequest->status,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Rescue Requests
    |--------------------------------------------------------------------------
    */

    public function updateRescueRequest(
        Request $request,
        int $id
    ) {
        $rescueRequest = RescueRequest::with('post')
            ->findOrFail($id);

        // فقط صاحب بلاغ الإنقاذ يقدر يقرر
        if ($rescueRequest->post->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to manage this request.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:ACCEPTED,REJECTED',
        ]);

        // ما نقدر نعدل طلب محسوم
        if ($rescueRequest->status !== 'PENDING') {
            return response()->json([
                'message' => 'This request has already been processed.'
            ], 409);
        }

        $rescueRequest->update([
            'status' => $validated['status'],
        ]);

        // إذا تمت الموافقة، نقفل بلاغ الإنقاذ
        if ($validated['status'] === 'ACCEPTED') {

            $rescueRequest->post->update([
                'status' => 'CLOSED',
            ]);

            // نرفض باقي الطلبات المعلقة
            RescueRequest::where('post_id', $rescueRequest->post_id)
                ->where('id', '!=', $rescueRequest->id)
                ->where('status', 'PENDING')
                ->update([
                    'status' => 'REJECTED',
                ]);
        }

        return response()->json([
            'message' => $validated['status'] === 'ACCEPTED'
                ? 'Rescue request accepted successfully.'
                : 'Rescue request rejected successfully.',

            'request' => [
                'id' => $rescueRequest->id,
                'post_id' => $rescueRequest->post_id,
                'status' => $rescueRequest->status,
            ],
        ]);
    }
}
