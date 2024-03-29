package fr.stvenchg.bleatz.activity;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import fr.stvenchg.bleatz.R;
import fr.stvenchg.bleatz.api.AuthenticationManager;
import fr.stvenchg.bleatz.tasks.RefreshTokenTask;

public class AuthActivity extends AppCompatActivity {

    private Button mLoginButton;
    private Button mRegisterButton;
    private TextView mLaterTextView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.splashscreen);

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                setContentView(R.layout.activity_auth);
                initializeViews();
                refreshTokenAndRedirect();
            }
        }, 1000); // Affiche l'écran de démarrage pendant 5 secondes
    }

    private void initializeViews() {
        mLoginButton = findViewById(R.id.auth_button_login);
        mRegisterButton = findViewById(R.id.auth_button_register);
        mLaterTextView = findViewById(R.id.auth_textview_later);

        // Démarrage de l'activité de connexion
        mLoginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(AuthActivity.this, LoginActivity.class);
                startActivity(intent);
                overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
            }
        });

        // Démarrage de l'activité d'inscription
        mRegisterButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(AuthActivity.this, RegisterActivity.class);
                startActivity(intent);
                overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
            }
        });
    }

    private void refreshTokenAndRedirect() {
        AuthenticationManager authenticationManager = new AuthenticationManager(this);
        String email = authenticationManager.getEmail();
        String refreshToken = authenticationManager.getRefreshToken();

        if (email != null && refreshToken != null) {
            // Exécutez l'AsyncTask pour rafraîchir le token et rediriger l'utilisateur
            RefreshTokenTask refreshTokenTask = new RefreshTokenTask(this, email, refreshToken, authenticationManager);
            refreshTokenTask.execute();
        }
    }
}
