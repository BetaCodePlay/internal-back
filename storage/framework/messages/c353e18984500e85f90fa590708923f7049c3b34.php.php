<?php


namespace App\Users\Repositories;

use App\Users\Entities\Document;
use App\Users\Entities\UserDocument;
use App\Users\Enums\DocumentStatus;

/**
 * Class UserDocumentsRepo
 *
 * This class allows to interact with UserDocument entity
 *
 * @package App\Users\Repositories
 * @author  Damelys Espinoza
 */
class UserDocumentsRepo
{
    /**
     * Find
     *
     * @param int $id Document ID
     * @return mixed
     */
    public function find($id)
    {
        $documents = UserDocument::find($id);
        return $documents;
    }

    /**
     * Delete
     *
     * @param int $id User document ID
     * @return mixed
     */
    public function delete($id)
    {
        $document = UserDocument::where('id', $id)
            ->whitelabel()
            ->first();
        $document->delete();
        return $document;
    }

    /**
     * Get document type name
     *
     * @param int $id Document type ID
     * @return mixed
     */
    public function documentTypeName($id)
    {
        $type = \DB::table('document_types')
            ->select('document_types.name')
            ->where('document_types.id', $id)
            ->first();
        return $type;
    }

    /**
     * Get documents by user
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $user User ID
     * @return mixed
     */
    public function documentByUser($whitelabel, $user)
    {
        $documents = UserDocument::select('user_documents.*', 'documents.translations', 'users.username')
            ->join('documents', 'user_documents.document_id', '=', 'documents.id')
            ->join('users', 'user_documents.user_id', '=', 'users.id')
            ->where('user_documents.whitelabel_id', $whitelabel)
            ->where('user_documents.user_id', $user)
            ->get();
        return $documents;
    }

    /**
     * Get documents pending
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function pending($whitelabel)
    {
        $documents = UserDocument::select('user_documents.*', 'documents.translations', 'documents.currency_iso', 'users.username')
            ->join('documents', 'user_documents.document_id', '=', 'documents.id')
            ->join('users', 'user_documents.user_id', '=', 'users.id')
            ->where('user_documents.whitelabel_id', $whitelabel)
            ->where('user_documents.status', DocumentStatus::$awaiting_verification)
            ->get();
        return $documents;
    }

    /**
     * Update
     *
     * @param int $id User document ID
     * @param array $data Document data
     * @return mixed
     */
    public function update($id, $data)
    {
        $documents = UserDocument::find($id)
             ->update($data);
        return $documents;
    }
}
