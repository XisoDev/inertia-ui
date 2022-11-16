# Form Handler for Inertia

---

- Inertia 기반의 프로젝트에서 FormUI를 생성하고 관리할 수 있습니다.
- JetStream과 완전히 통합됩니다.
- 각각의 Form은 배포된 ```_Loader{valueType}.vue``` 파일 내부를 수정하여 스킨을 교체할 수 있습니다.

## install

- vite 의 정상적인 작동을 위해 세개의 파일 수정이 필요합니다.
```html
<!--// app.blade.php -->
...
@routes

<!---// 여기서부터 시작하는 @vite 구문을 대체합니다. -->
@if($componentObj = json_decode($page['component']))
@vite(['resources/js/app.js', "resources/{$page['props']['path']}{$componentObj->component}.vue"])
@else
@vite(['resources/js/app.js', "resources/{$page['props']['path']}/{$page['component']}.vue"])
@endif
<!--// 여기까지-->

@inertiaHead
...
```
```javascript
/**
 * vite.config.js 파일에 다음과같이 resolve 경로를 선언 해 줍니다. 
 */

//...
resolve: {
    alias: [
        // ...
        {
            find: '@themes',
            replacement: path.resolve( __dirname, '/resources/themes/' )
        },
        {
            find: '@fieldLoader',
            replacement: path.resolve( __dirname, '/resources/FieldLoader/' )
        }
        //...
    ]
}
//...
```

```javascript
/**
 * app.js 의 resolve 를 변경합니다.
 */
//...
resolve: (name) => {
    try {
        let componentObj = JSON.parse(name);
        console.log(componentObj);
        return resolvePageComponent(`../${componentObj.path}${componentObj.component}.vue`, import.meta.glob('../**/**/*.vue'))
    } catch (e) {
        // JetStream의 순정파일을 이동 했다면, 아래 경로를 함께 변경해야 합니다.
        return resolvePageComponent(`./js/Pages/${name}.vue`, import.meta.glob('./js/Pages/**/*.vue'))
    }
}
//...
```

파일수정 및 준비가 완료되었다면, 다음 아티산커맨드를 통해 `fieldloader` 가 `resources` 디렉터리 내에 게시될 수 있도록 해야합니다.
```shell
php artisan xiso:ui-install
```

번역, 설정 등의 커스텀을 위해서 다음 명령을 통해 필요한파일을 프로젝트 안으로 퍼블리싱 할 수도 있습니다.
```shell
php artisan vendor:publish --provider="Xiso\InertiaUI\InertiaUIServiceProvider"
```

### FieldLoader

--- 

- 필드로더는 ```_LoaderString.vue``` 및 ```_loaderObject.vue``` 등 폼필드를 통해 입력받고자 하는 값의 유형에 따라 적절한 로드를 폼필드에서 호출해야 합니다.
- 특정필드를 직접 만들고싶다면 해당 로더를 복제하여 호출 한 후 커스텀하고싶은 필드만 별도로 ```import``` 해주면 됩니다.

#### 지원되는 필드

- String
    - Input Text
    - Input Radio
    - Input Date
    - Radio Button (with Groups)
    - Select (Single-Dropdown)
    - Textarea
- Object
  - Image (with Multiple)
  - File
