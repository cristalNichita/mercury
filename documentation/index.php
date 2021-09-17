<?php
/**
 * @OA\Info(
 *   title="Mercury site api",
 *   version="1.0",
 *   @OA\Contact(
 *     email="info@echo-company.ru",
 *     name="Компания ЭХО",
 *     url="https://echo-company.ru"
 *   )
 * )
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer"
 * )
 * @OA\Server(url=L5_SWAGGER_CONST_HOST)
 * @OA\Get(
 *   path="/",
 *   security={ {"bearerAuth": {} }},
 *   description="Статус, проверки токена авторизации, ссылка на документацию",
 *   @OA\Response(response="200", description="ok")
 * )
 */
