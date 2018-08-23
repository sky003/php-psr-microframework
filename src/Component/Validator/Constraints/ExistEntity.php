<?php
declare(strict_types = 1);

namespace App\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * The constraint to check that identifier, foreign key, or any
 * other fields exists in the database.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 *
 * @Annotation()
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ExistEntity extends Constraint
{
    public const NOT_EXIST_ERROR = '5f309c12-be7b-48dc-9dab-67a82b25024b';

    public $message = 'This value is not exist.';
    /**
     * @var string
     */
    public $entityClass;
    public $properties = [];
    public $repositoryMethod = 'findBy';

    protected static $errorNames = [
        self::NOT_EXIST_ERROR => 'NOT_EXIST_ERROR',
    ];

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions(): array
    {
        return ['entityClass', 'properties'];
    }
}
