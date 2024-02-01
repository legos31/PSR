<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Framework\Authentication\SessionAuthInterface;
use Framework\Controller\AbstractController;
use Framework\Http\RedirectResponse;
use Framework\Http\Response;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private SessionAuthInterface $auth
    ) {
    }

    public function form(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register()
    {
        // 1. Создайте модель формы, которая будет:
        $form = new RegisterForm($this->userService);

        $form->setFields(
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('password_confirmation'),
            $this->request->input('name'),
        );

        // 2. Валидация
        // Если есть ошибки валидации, добавить в сессию и перенаправить на форму

        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $error) {
                $this->request->getSession()->setFlash('error', $error);
            }

            return new RedirectResponse('/register');
        }

        // 3. Зарегистрировать пользователя, вызвав $form->save()

        $user = $form->save();

        // 4. Добавить сообщение об успешной регистрации

        $this->request->getSession()->setFlash('success', "Пользователь {$user->getEmail()} успешно зарегистрирован");

        // 5. Войти в систему под пользователем

        $this->auth->login($user);

        // 6. Перенаправить на нужную страницу

        return new RedirectResponse('/dashboard');
    }
}
