<?php 

namespace App\Dtos;

use App\Constants\RoleConstant;
use Billyranario\ProstarterKit\App\Dtos\BaseDto;

class UserDto extends BaseDto
{
    /**
     * @var int $id
     * @var string $firstname
     * @var string $lastname
     * @var string $email
     * @var string $password
     * @var bool $remember
     * @var string $resetToken
     * @var int $roleId
     * @var string $newPassword
     * @var string $theme
     */
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private bool $remember = false;
    private string $resetToken = '';
    private int $roleId = RoleConstant::CLIENT;
    private string $newPassword = '';
    private string $theme = '';

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id; 
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname; 
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname; 
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email; 
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password; 
    }

    /**
     * @param bool $remember
     */
    public function setRemember(bool $remember): void
    {
        $this->remember = $remember; 
    }
    
    /**
     * @param string $resetToken
     */
    public function setResetToken(string $resetToken): void
    {
        $this->resetToken = $resetToken; 
    }

    /**
     * @param int $roleId
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId; 
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword(string $newPassword): void
    {
        $this->newPassword = $newPassword; 
    }

    /**
     * @param string $theme
     */
    public function setTheme(string $theme): void
    {
        $this->theme = $theme; 
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id; 
    }
    
    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname; 
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname; 
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email; 
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password; 
    }

    /**
     * @return bool
     */
    public function getRemember(): bool
    {
        return $this->remember; 
    }

    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken; 
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->roleId; 
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword; 
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme; 
    }
}   