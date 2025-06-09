<?php

use App\Http\Requests\StoreAlimentoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


beforeEach(function () {
    $this->validData = [
        'nombre' => 'Pollo',
        'calorias' => 120,
        'proteinas' => 25,
        'carbohidratos' => 0,
        'grasas' => 3,
        'cantidad' => 100,
        'dia' => 'Lunes',
        'tipo_comida' => 'Almuerzo',
    ];
});


function validate(array $data)
{
    $request = new StoreAlimentoRequest();
    return Validator::make($data, $request->rules());
}

function validateWithPreparation(array $data)
{
    $request = new StoreAlimentoRequest();
    $request->merge($data);
    $request->prepareForValidation(); // ← ejecuta el hook

    return Validator::make($request->all(), $request->rules());
}



function validateRequest(array $data): \Illuminate\Contracts\Validation\Validator
{
    $request = Request::create('/fake-url', 'POST', $data);
    $formRequest = new StoreAlimentoRequest();

    $formRequest->setContainer(app())->setRedirector(app('redirect'));
    $formRequest->merge($request->all());

    return Validator::make($formRequest->all(), $formRequest->rules());
}

function reglasStoreAlimento(): array {
    return [
        'nombre' => 'required|string|max:255',
        'calorias' => 'required|numeric|min:0',
        'proteinas' => 'required|numeric|min:0',
        'carbohidratos' => 'required|numeric|min:0',
        'grasas' => 'required|numeric|min:0',
        'cantidad' => 'required|numeric|min:1',
        'dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
        'tipo_comida' => 'required|in:Desayuno,Almuerzo,Comida,Merienda,Cena',
    ];
}


test('passes with valid data', function () {
    $validator = validate($this->validData);
    expect($validator->fails())->toBeFalse();
});

test('nombre is required', function () {
    $data = $this->validData;
    unset($data['nombre']);
    expect(validate($data)->fails())->toBeTrue();
});

test('calorias must be numeric and >= 0', function () {
    expect(validate(array_merge($this->validData, ['calorias' => -1]))->fails())->toBeTrue();
    expect(validate(array_merge($this->validData, ['calorias' => 'abc']))->fails())->toBeTrue();
});

test('proteinas must be numeric and >= 0', function () {
    expect(validate(array_merge($this->validData, ['proteinas' => -1]))->fails())->toBeTrue();
    expect(validate(array_merge($this->validData, ['proteinas' => 'abc']))->fails())->toBeTrue();
});

test('carbohidratos must be numeric and >= 0', function () {
    expect(validate(array_merge($this->validData, ['carbohidratos' => -1]))->fails())->toBeTrue();
    expect(validate(array_merge($this->validData, ['carbohidratos' => 'abc']))->fails())->toBeTrue();
});

test('grasas must be numeric and >= 0', function () {
    expect(validate(array_merge($this->validData, ['grasas' => -1]))->fails())->toBeTrue();
    expect(validate(array_merge($this->validData, ['grasas' => 'abc']))->fails())->toBeTrue();
});

test('el request permite usuarios autenticados', function () {
    $request = new StoreAlimentoRequest();
    $this->assertTrue($request->authorize());
});

test('pasa la validación con datos correctos', function () {
    $validator = Validator::make($this->validData, (new StoreAlimentoRequest())->rules());
    expect($validator->fails())->toBeFalse();
});

test('nombre es requerido', function () {
    $data = $this->validData;
    unset($data['nombre']);

    $validator = Validator::make($data, (new StoreAlimentoRequest())->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('nombre'))->toBeTrue();
});

test('calorias debe ser numérico y mayor o igual a 0', function () {
    $data = $this->validData;
    $data['calorias'] = -10;

    $validator = Validator::make($data, (new StoreAlimentoRequest())->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('calorias'))->toBeTrue();
});

test('proteinas, carbohidratos y grasas deben ser numéricos y positivos', function () {
    foreach (['proteinas', 'carbohidratos', 'grasas'] as $campo) {
        $data = $this->validData;
        $data[$campo] = -1;

        $validator = Validator::make($data, (new StoreAlimentoRequest())->rules());
        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->has($campo))->toBeTrue();
    }
});

test('cantidad es requerida y al menos 1', function () {
    $data = $this->validData;
    $data['cantidad'] = 0;

    $validator = Validator::make($data, reglasStoreAlimento());
    dump($validator->errors()->all()); // <-- quita esto al final
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('cantidad'))->toBeTrue();
});

test('dia debe ser uno de los días válidos', function () {
    $data = $this->validData;
    $data['dia'] = 'Funday';

    $validator = Validator::make($data, reglasStoreAlimento());
    dump($validator->errors()->all()); // <-- quita esto al final
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('dia'))->toBeTrue();
});

test('tipo_comida debe ser uno de los valores válidos', function () {
    $data = $this->validData;
    $data['tipo_comida'] = 'Brunch';

    $validator = Validator::make($data, reglasStoreAlimento());
    dump($validator->errors()->all()); // <-- quita esto al final
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('tipo_comida'))->toBeTrue();
});
