<?php
/**
 * @OA\Post(
 *  path="/user/register",
 *  summary="Регистрация пользователя по телефону или email",
 *  description="
 *  Регистрация по телефону: ожидает телефон, высылает СМС с кодом, регистрирует (с отправкой пароля в смс) или авторизует
 *  Регистрация по email: ожидает email и пароль, регистрирует или авторизует
 *  [Подробности](https://echocompany.bitrix24.ru/disk/showFile/119238/?&ncc=1&ts=1625923497&filename=%D0%A1%D1%85%D0%B5%D0%BC%D0%B0+%D0%B0%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D0%B8.docx)",
 *  tags={"Пользователи (Модуль User)"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     description="Тип регистрации",
 *                     property="type",
 *                     type="string",
 *                     enum={"email", "phone"},
 *                     default="phone"
 *                 ),
 *                 @OA\Property(
 *                     description="Email",
 *                     property="email",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     description="Телефон в произвольном формате",
 *                     property="phone",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     description="Код авторизации для телефона",
 *                     property="code",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     description="Пароль",
 *                     property="password",
 *                     type="string",
 *                 ),
 *                 required={"type"}
 *             )
 *         )
 *     ),
 *
 *
 *     @OA\Response(
 *         response="200 (1)",
 *         description="Отправлен код доступа на телефон, countdown - кол-во секунд до повторной отправки",
 *         @OA\JsonContent(
 *             example={
 *                  "send_code": true,
 *                  "message": "Код отправлен вам по смс",
 *                  "countdown": 34
 *             },
 *         ),
 *     ),
 *
 *
 *     @OA\Response(
 *         response="200 (2)",
 *         description="Пользователь зарегистрирован/авторизован",
 *         @OA\JsonContent(
 *              example={
 *                  "token": "токен доступа",
 *                  "user": "user object"
 *              },
 *         ),
 *     ),
 *
 *
 *     @OA\Response(
 *          response="422",
 *          description="Неверные данные (стандартный механизм laraver, обработка во фронте функцией catchLaravelError)",
 *          @OA\JsonContent(
 *              example={
 *                  "error": "Произошла ошибка",
 *                  "fields": {
 *                      "email": {
 *                          "Обязательное поле"
 *                      },
 *                      "password": {
 *                          "Обязательное поле"
 *                      }
 *                  },
 *              }
 *          ),
 *      ),
 *  )
 */
