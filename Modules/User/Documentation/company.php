<?php
/**
 * @OA\Get(
 *  path="/user/company",
 *  security={ {"bearerAuth": {} }},
 *  summary="Получение списка компаний пользователя",
 *  description="",
 *  tags={"Пользователи (Модуль User)"},
 *
 *     @OA\Response(
 *         response="200",
 *         description="Список компаний пользователя",
 *         @OA\JsonContent(
 *             example={
 *                  {
 *                       "id": 11,
 *                       "holding_id": 2,
 *                       "name": "Компания-4",
 *                       "guid": null,
 *                       "guid_site": "96479dea-c6dc-4088-8292-abb2e793c7a7",
 *                       "type": 1,
 *                       "type_1c": "Юридическое лицо",
 *                       "inn": "123456789111",
 *                       "kpp": "123456789",
 *                       "ogrn": "1234567891234",
 *                       "created_at": "2021-07-16T09:16:05.000000Z",
 *                       "updated_at": "2021-07-16T09:16:05.000000Z",
 *                       "params": "array params object",
 *                       "bank_requisites": "array bank_requisites object"
 *                   }
 *             },
 *         ),
 *     ),
 *
 *     @OA\Response(
 *          response="500",
 *          description="Ошибка ауентификации",
 *          @OA\JsonContent(
 *              example={
 *                  "error": "Unauthenticated."
 *              }
 *          ),
 *      ),
 *  ),
 *
 * @OA\Post(
 *  path="/user/company",
 *  security={ {"bearerAuth": {} }},
 *  summary="Создание компании",
 *  description="",
 *  tags={"Пользователи (Модуль User)"},
 *
 *     @OA\RequestBody(
 *          required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     description="Наименование компании",
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     description="ИНН компании",
 *                     property="inn",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     description="КПП компании",
 *                     property="kpp",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     description="ОГРН",
 *                     property="ogrn",
 *                     type="string",
 *                 ),
 *                 required={"name", "inn", "kpp", "ogrn"}
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Добавленный пользователь",
 *     ),
 *     @OA\Response(
 *          response="422",
 *          description="Неверные данные (стандартный механизм laraver, обработка во фронте функцией catchLaravelError)",
 *          @OA\JsonContent(
 *              example={
 *                  "error": "Произошла ошибка",
 *                  "fields": {
 *                      "name": {
 *                          "Обязательное поле"
 *                      },
 *                      "inn": {
 *                          "Обязательное поле"
 *                      }
 *                  },
 *              }
 *          ),
 *      ),
 *     @OA\Response(
 *          response="500",
 *          description="Ошибка ауентификации",
 *          @OA\JsonContent(
 *              example={
 *                  "error": "Unauthenticated."
 *              }
 *          ),
 *      ),
 * ),
 * @OA\PUT(
 *  path="/user/company/{company_id}",
 *  security={ {"bearerAuth": {} }},
 *  summary="Обновление компании",
 *  description="",
 *  tags={"Пользователи (Модуль User)"},
 *      @OA\Response(
 *         response="200",
 *         description="Обновленный пользователь",
 *     ),
 *
 *     @OA\Response(
 *          response="500",
 *          description="Ошибка ауентификации",
 *          @OA\JsonContent(
 *              example={
 *                  "error": "Unauthenticated."
 *              }
 *          ),
 *      ),
 * ),
 *
 * @OA\Delete (
 *  path="/user/company/{company_id}",
 *  security={ {"bearerAuth": {} }},
 *  summary="Удаление компании",
 *  description="Удаляется компания, ее реквизиты и параметры (адреса, телефоны, email)",
 *  tags={"Пользователи (Модуль User)"},
 *      @OA\Response(
 *         response="200",
 *         description="Результат удаления",
 *     ),
 *
 *     @OA\Response(
 *          response="500",
 *          description="Ошибка ауентификации",
 *          @OA\JsonContent(
 *              example={
 *                  "error": "Unauthenticated."
 *              }
 *          ),
 *      ),
 * ),
*/
