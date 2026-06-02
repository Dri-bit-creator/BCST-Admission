// Firebase initialization placeholder.
// 1) Include Firebase SDKs in your HTML before this script:
// <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
// <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>
// <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-firestore-compat.js"></script>
// <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-storage-compat.js"></script>
// 2) Replace the firebaseConfig object below with your project's credentials.

(function () {
  if (typeof firebase === 'undefined') {
    console.error('Firebase SDK not loaded. Include the SDK scripts before this file.');
    return;
  }

  const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT.firebaseapp.com",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_PROJECT.appspot.com",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
  };

  if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
  }

  const db = firebase.firestore();
  const storage = firebase.storage();
  const auth = firebase.auth();

  // Expose small helpers on window for convenience
  window.fb = {
    firebase,
    db,
    storage,
    auth,
    generateUniqueStudentId: async function generateUniqueStudentId() {
      for (let i = 0; i < 10; i++) {
        const candidate = String(Math.floor(100000 + Math.random() * 900000));
        try {
          const snap = await db.collection('students').where('student_id', '==', candidate).limit(1).get();
          if (snap.empty) return candidate;
        } catch (err) {
          console.error('Firestore check error', err);
          return candidate;
        }
      }
      return 'ID' + Date.now().toString().slice(-6);
    }
  };
})();
