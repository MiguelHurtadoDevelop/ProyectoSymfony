<?php

namespace App\Security;

use App\Entity\Restaurante as AppRestaurante; // Update namespace and class name
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIsVerified implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        
        
        if (!$user instanceof AppRestaurante) { // Update class name
            return;
        }
        
        if (!$user->isVerified()) { // Update method name
            
            // The message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Todavía no has verificado tu cuenta.');
            
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // You can perform additional checks after authentication if needed
    }
}
