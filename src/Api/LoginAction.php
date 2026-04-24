<?php
declare(strict_types=1);

namespace App\Api;

use App\Api\Shared\ResponseFactory;
use App\Entity\Repository\UserRepository;
use App\Entity\Repository\UserTokenRepository;
use App\Entity\UserTokenType;
use App\Shared\ApplicationParams;
use DateTimeImmutable;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Http\Status;

final class LoginAction
{
    public function __construct(
        private UserRepository      $userRepository,
        private UserTokenRepository $userTokenRepository,
    )
    {
    }

    /**
     * @param ResponseFactory $responseFactory
     * @param ApplicationParams $applicationParams
     * @param ContainerInterface $container
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function __invoke(
        ApplicationParams      $applicationParams,
        ResponseFactory        $responseFactory,
        ContainerInterface     $container,
        ServerRequestInterface $request
    ): ResponseInterface
    {
        $data = json_decode((string)$request->getBody(), true);

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            return $responseFactory->fail(
                'Invalid user',
                httpCode: Status::UNAUTHORIZED
            );
        }

        $token = $this->userTokenRepository->findTokenByType($user->getId(), UserTokenType::WEB_PASSWORD_HASH);

        if (!$user->validatePassword($password, $token)) {
            return $responseFactory->fail(
                'Invalid password',
                httpCode: Status::UNAUTHORIZED
            );
        }

        $this->userTokenRepository->delete([
            'id_user' => $user->getId(),
            'id_type' => UserTokenType::API_BEARER,
        ]);

        $userToken = $this->userTokenRepository->createApiBearer($user->getId());

        return $responseFactory->success([
            'token' => $userToken->getToken(),
            'expires_at' => $userToken->getExpiresAt()->format(DateTimeImmutable::ATOM),
        ]);
    }

}
