<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nombre
 * @property string $categoria
 * @property string|null $imagen
 * @property int $calorias
 * @property string $proteinas
 * @property string $carbohidratos
 * @property string $grasas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $usuarios
 * @property-read int|null $usuarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereCalorias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereCarbohidratos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereGrasas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereImagen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereProteinas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alimento whereUpdatedAt($value)
 */
	class Alimento extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $semana
 * @property string|null $dieta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DietaAlimento> $alimentos
 * @property-read int|null $alimentos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta deSemanaActual($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta whereDieta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta whereSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dieta whereUserId($value)
 */
	class Dieta extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $dieta_id
 * @property int $alimento_id
 * @property string $dia
 * @property string $tipo_comida
 * @property int $cantidad
 * @property int $consumido
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Alimento $alimento
 * @property-read \App\Models\Dieta $dieta
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento consumidos($dietaId, $dia)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento delDia($dietaId, $dia)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereAlimentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereConsumido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereDia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereDietaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereTipoComida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DietaAlimento whereUpdatedAt($value)
 */
	class DietaAlimento extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $gender
 * @property int|null $age
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $peso
 * @property int|null $altura
 * @property string|null $objetivo
 * @property string|null $actividad
 * @property int|null $calorias_necesarias
 * @property int|null $proteinas
 * @property int|null $carbohidratos
 * @property int|null $grasas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alimento> $alimentos
 * @property-read int|null $alimentos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Alimento> $alimentosFavoritos
 * @property-read int|null $alimentos_favoritos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $clientes
 * @property-read int|null $clientes_count
 * @property-read \App\Models\Dieta|null $dieta
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dieta> $dietas
 * @property-read int|null $dietas_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $nutricionista
 * @property-read int|null $nutricionista_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAltura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCaloriasNecesarias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCarbohidratos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGrasas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereObjetivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProteinas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

