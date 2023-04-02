package com.example.bleatz;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.textfield.TextInputLayout;

public class ConnexionActivity extends AppCompatActivity {


    private TextInputLayout login ;
    private TextInputLayout password ;
    private TextView sincrire;
    private Button connexionbutton ;




    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.connexion_activity);

        login = findViewById(R.id.text_input_layout_login);
        password = findViewById(R.id.text_input_layout_password);
        sincrire = findViewById(R.id.text_view_s_inscrire);
        connexionbutton = findViewById(R.id.button_connexion);


        sincrire.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(ConnexionActivity.this, InscriptionActivity.class);
                startActivity(intent);
            }
        });
    }
}
