<?php
namespace App\Service;

use App\Entity\User;
use App\Exception\HttpAuthenticationTimeoutException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Auth
 * @package App\Service
 */
class Auth
{
    /**
     * @var string
     */
    protected $createdAt;
    /**
     * @var string
     */
    protected $expiresAt;
    /**
     * @var string
     */
    protected $fullName;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var
     */
    protected $jwtEncoder;
    /**
     * @var array
     */
    protected $roles = [User::ROLE_GUEST];
    /**
     * @var
     */
    protected $token;

    public function __construct(TokenStorageInterface $tokenStorage, JWTEncoderInterface $jwtEncoder)
    {
        $this->token = $tokenStorage->getToken()->getCredentials();
        $this->jwtEncoder = $jwtEncoder;

        if ($this->token) {
            $decodedToken = $this->jwtEncoder->decode($this->token);
            $this->createdAt = $decodedToken['iat'];
            $this->expiresAt = $decodedToken['exp'];
            $this->fullName = $decodedToken['fullName'];
            $this->id = $decodedToken['id'];
            $this->roles = $decodedToken['roles'];
        }
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return ($this->roles && (!in_array(User::ROLE_GUEST, $this->roles)));
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string
     * @throws HttpAuthenticationTimeoutException
     */
    public function getToken()
    {
        if (time() < ($this->getExpiresAt())) {
            return $this->jwtEncoder->encode([
                'fullName' => $this->getFullName(),
                'id' => $this->getId(),
                'roles' => $this->getRoles()
            ]
            );
        } else {
            throw new HttpAuthenticationTimeoutException('Authentication timeout!!!');
        }
    }
}