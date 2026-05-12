<?php

declare(strict_types=1);

class EmailAlreadyExistsException extends \Exception
{
}

class UserRegistration
{
    public function register(string $email, int $age): void
    {
        if ($email === '') {
            throw new \InvalidArgumentException("メールアドレスは必須です");
        }

        if ($age < 0 || $age > 150) {
            throw new \OutOfRangeException("年齢は0〜150の範囲で指定してください");
        }

        if ($email === 'existing@example.com') {
            throw new EmailAlreadyExistsException("{$email} は既に登録されています");
        }

        echo "登録完了" . PHP_EOL;
    }
}

$registration = new UserRegistration();

$testCases = [
    ['', 30],
    ['valid@example.com', 200],
    ['existing@example.com', 25],
    ['success@example.com', 30],
];

foreach ($testCases as [$email, $age]) {
    try {
        $registration->register($email, $age);
    } catch (\InvalidArgumentException $e) {
        echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
    } catch (\OutOfRangeException $e) {
        echo "エラー(範囲外): " . $e->getMessage() . PHP_EOL;
    } catch (EmailAlreadyExistsException $e) {
        echo "エラー(重複): " . $e->getMessage() . PHP_EOL;
    } finally {
        echo "処理を試みました" . PHP_EOL;
        echo "---" . PHP_EOL;
    }
}