<?php


namespace AppBundle\Services\MailChimp;


use AppBundle\Entity\User;

class MailChimpManager
{
    private $apiKey;
    private $listId;

    public function __construct($apiKey, $listId)
    {
        $this->apiKey = $apiKey;
        $this->listId = $listId;
    }

    public function syncMailchimp($data) {

        $memberId = md5(strtolower($data['email']));
        $dataCenter = substr($this->apiKey,strpos($this->apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $this->listId . '/members/' . $memberId;

        $json = json_encode([
            'email_address' => $data['email'],
            'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
            'merge_fields'  => [
                'FNAME'     => $data['firstname'],
                'LNAME'     => $data['lastname']
            ]
        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode;
    }

    public function subscribeNewsletter(User $user)
    {
        $data = [
            'email'     => $user->getEmail(),
            'status'    => 'subscribed',
            'firstname' => $user->getFirstname(),
            'lastname'  => $user->getName()
        ];

        $this->syncMailchimp($data);
    }

    public function unsubscribeNewsletter(User $user)
    {
        $data = [
            'email'     => $user->getEmail(),
            'status'    => 'unsubscribed',
            'firstname' => $user->getFirstname(),
            'lastname'  => $user->getName()
        ];

        $this->syncMailchimp($data);
    }
}
