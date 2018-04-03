###Technologies
This app developed using `Cordova` and `Ionic`. You will need `node.js` installed on your system to use `Cordova`. App can be build using any windows, linux or unix computer. **For iOS build you will need any Macintosh operated system.**


###Requirements
You will need `JDK 8` for `Android App` (newest version of `JDK` doesn't works with `Android SDK`), which can be downloaded from here : `http://www.oracle.com/technetwork/java/javase/downloads/jdk8-downloads-2133151.html`.

You will need `Android SDK` or `Android Studio` for `Android App`, which can be downloaded from here  : `https://developer.android.com/studio/index.html`

You will need `XCode` with `Xcode Commandline Tool` for `iOS App`, you can download this from your `Macintosh` system's `App Store`.

You will need `Apple Developer Program Enrollment` for `iOS App`.

You can find more about how to download and install `node.js` here : `https://nodejs.org/en/download/`.

You can find more about how to download and install `Cordova` and `Ionic` here : `http://ionicframework.com/getting-started/`.


###Building
Before you prepare the building process you have to change the API Server End Point (`apiEndPoint`) on the app source code. Extract and place source codes from `safariPark.zip` on your desktop. Navigate to `safariPark` -> `src` -> `providers` -> `api-end` and use any standard editor to modify the `api-end.ts` there with bellow information :

```
//TODO Change URL for apiEndPoint
    public apiEndPoint: string = 'https://demo.mywire.org/api/v2/';
```

Change `https://demo.mywire.org/api/v2/` with your app installed server's location.

Once the API End Point has been changed and you are done with all requirements you can Prepare your source codes for Production build.
Use system `terminal` app, `cd` to app source code's root directory. Eg. `cd ~/Desktop/safariPark`.

For more information about how to build and publish on app store follow this link : `https://ionicframework.com/docs/intro/deploying/`
