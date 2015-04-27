<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @param Request $request
     * @Route("/email-change", name="app_email_change")
     * @Method("GET")
     * @return JsonResponse
     * @Cache(smaxage="60")
     */
    public function emailChangeAction(Request $request)
    {
        try {
            $resolved = $this->resolveParams($request->query->all());
            $mailChange = $this->get('user_service');
            $mailChange->changeEmail($resolved['old_email'], $resolved['new_email']);
        } catch (\Exception $exception) {
            return new JsonResponse(['code' => 500, 'message' => $exception->getMessage()]);
        }

        return new JsonResponse(['status' => 200, 'message' => 'Email successfully changed.']);

    }

    /**
     * @param array $array
     * @return mixed
     */
    protected function resolveParams(array $array)
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefined(['old_email', 'new_email']);
        $optionsResolver->setRequired(['old_email', 'new_email']);
        $optionsResolver->setAllowedTypes('old_email', ['string']);
        $optionsResolver->setAllowedTypes('new_email', ['string']);
        $optionsResolver->setAllowedValues(
            'old_email',
            function ($value) {
                return filter_var($value, FILTER_VALIDATE_EMAIL);
            }
        );
        $optionsResolver->setAllowedValues(
            'new_email',
            function ($value) {
                return filter_var($value, FILTER_VALIDATE_EMAIL);
            }
        );

        return $optionsResolver->resolve($array);
    }
}
