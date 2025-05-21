<?php

it('renders the register page', function () {
get(route('register'))
->assertStatus(200)
->assertSee('Registro') // Ajusta según el contenido de la vista
->assertSee('name')
->assertSee('email')
->assertSee('password');
});

it('renders the login page', function () {
get(route('login'))
->assertStatus(200)
->assertSee('Iniciar sesión') // Ajusta según el contenido de la vista
->assertSee('email')
->assertSee('password');
});
