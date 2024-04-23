<?php


namespace App\CRM\Repositories;

use App\CRM\Entities\EmailTemplate;

/**
 * Class EmailTemplatesRepo
 *
 * This class allows to interact with EmailTemplate entity
 *
 * @package App\CRM\Repositories
 * @author  Carlos Hurtado
 */
class EmailTemplatesRepo
{
    /**
     * Get all email
     *
     * @return mixed
     */
    public function all()
    {
        return EmailTemplate::whitelabel()->get();
    }

    /**
     * Get all transactions email
     *
     * @return mixed
     */
    public function allTransactions($emailTemplateTypeId)
    {
        return EmailTemplate::whitelabel()->whereIn('email_templates_type_id', $emailTemplateTypeId )
            ->get();
    }

    /**
     * Delete template
     *
     * @param int $id Template ID
     * @return mixed
     */
    public function delete($id)
    {
        $template = EmailTemplate::where('id', $id)
            ->whitelabel()
            ->first();
        $template->delete();
        return $template;
    }

    /**
     * Find by ID
     *
     * @param int $id Template ID
     * @return mixed
     */
    public function find($id)
    {
        return EmailTemplate::where('id', $id)
            ->first();
    }

    /**
     * Get by language and currency
     *
     * @param string $language Language ISO
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getByLanguageAndCurrency($language, $currency)
    {
        return EmailTemplate::select('email_templates.id', 'email_templates.title')
            ->where('language', $language)
            ->where('currency_iso', $currency)
            ->whitelabel()
            ->get();
    }

    /**
     * Store templates
     *
     * @param array $data Template data
     * @return mixed
     */
    public function store($data)
    {
        return EmailTemplate::create($data);
    }

    /**
     * Update template
     *
     * @param int $id Template ID
     * @param array $data Template data
     * @return mixed
     */
    public function update($id, $data)
    {
        $template = EmailTemplate::find($id);
        $template->fill($data);
        $template->save();
        return $template;
    }
}
