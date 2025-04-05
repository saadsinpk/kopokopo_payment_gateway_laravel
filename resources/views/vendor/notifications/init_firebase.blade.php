var firebaseConfig = {
  apiKey: "{{setting('firebase_apikey','')}}",
  authDomain: "{{setting('firebase_authdomain','')}}",
  databaseURL: "{{setting('firebase_databaseurl','')}}",
  projectId: "{{setting('firebase_projectid','')}}",
  storageBucket: "{{setting('firebase_storagebucket','')}}",
  messagingSenderId: "{{setting('firebase_messagingsenderid','')}}",
  appId: "{{setting('firebase_appid')}}",
  measurementId: "{{setting('firebase_measurementid','')}}"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
