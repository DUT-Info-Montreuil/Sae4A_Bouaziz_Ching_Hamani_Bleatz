package com.example.bleatz;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.textfield.TextInputLayout;

public class ConnexionActivity extends AppCompatActivity {


    private TextInputLayout login ;
    private TextInputLayout password ;
    private TextView sincrire;
    private Button button ;




    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.connexion_activity);

        login = findViewById(R.id.text_input_layout_login);
        password = findViewById(R.id.text_input_layout_password);
        sincrire = findViewById(R.id.text_view_s_inscrire);
        button = findViewById(R.id.button_connexion);


    }
}
