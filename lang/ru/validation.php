<?php

return [
    'accepted' => 'Поле :attribute должно быть принято.',
    'array' => 'Поле :attribute должно быть массивом.',
    'boolean' => 'Поле :attribute должно быть логическим значением.',
    'confirmed' => 'Поле :attribute не совпадает с подтверждением.',
    'current_password' => 'Неверный текущий пароль.',
    'email' => 'Поле :attribute должно быть действительным email-адресом.',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'image' => 'Поле :attribute должно быть изображением.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'max' => [
        'array' => 'Поле :attribute не должно содержать более :max элементов.',
        'file' => 'Поле :attribute не должно быть больше :max килобайт.',
        'numeric' => 'Поле :attribute не должно быть больше :max.',
        'string' => 'Поле :attribute не должно быть длиннее :max символов.',
    ],
    'min' => [
        'array' => 'Поле :attribute должно содержать не менее :min элементов.',
        'file' => 'Поле :attribute должно быть не меньше :min килобайт.',
        'numeric' => 'Поле :attribute должно быть не меньше :min.',
        'string' => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    'required' => 'Поле :attribute обязательно для заполнения.',
    'string' => 'Поле :attribute должно быть строкой.',
    'unique' => 'Такое значение поля :attribute уже существует.',

    'attributes' => [
        'email' => 'email',
        'password' => 'пароль',
        'current_password' => 'текущий пароль',
        'title' => 'название',
        'description' => 'описание',
        'cover' => 'обложка',
        'images' => 'изображения',
        'image' => 'изображение',
        'start_number' => 'начальный номер',
        'page_number' => 'номер страницы',
        'label' => 'метка',
        'is_published' => 'публикация',
        'is_active' => 'активность',
    ],
];
