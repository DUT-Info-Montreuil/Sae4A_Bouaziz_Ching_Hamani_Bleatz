package com.example.bleatz;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

import com.google.android.material.textfield.TextInputLayout;

public class MainActivity extends AppCompatActivity {


    private Button seConnecterbutton ;
    private Button sIncrirebutton ;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        seConnecterbutton = findViewById(R.id.button_se_connecter);
        sIncrirebutton = findViewById(R.id.button_s_inscrire);

    }
}