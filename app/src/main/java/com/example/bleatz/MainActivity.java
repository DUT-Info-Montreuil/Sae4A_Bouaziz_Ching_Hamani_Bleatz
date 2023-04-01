package com.example.bleatz;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

import com.google.android.material.textfield.TextInputLayout;

public class MainActivity extends AppCompatActivity {

    private TextInputLayout login ;
    private TextInputLayout password ;
    private TextView sincrire;
    private Button button ;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        login = findViewById(R.id.text_input_layout_login);
        password = findViewById(R.id.text_input_layout_password);
        sincrire = findViewById(R.id.text_view_s_inscrire);
        button = findViewById(R.id.button_connexion);


    }
}