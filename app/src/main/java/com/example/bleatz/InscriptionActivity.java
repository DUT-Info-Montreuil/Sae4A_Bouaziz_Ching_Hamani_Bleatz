package com.example.bleatz;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.textfield.TextInputLayout;

public class InscriptionActivity extends AppCompatActivity {

    private TextInputLayout login ;
    private TextInputLayout password ;
    private TextInputLayout email ;
    private TextInputLayout address ;
    private TextInputLayout phone ;
    private TextView seConnecter;
    private Button inscriptionButton ;




    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.inscription_activity);

        login = findViewById(R.id.text_input_layout_login);
        password = findViewById(R.id.text_input_layout_password);
        seConnecter = findViewById(R.id.text_view_se_connecter);
        inscriptionButton = findViewById(R.id.button_inscription);
        email = findViewById(R.id.text_input_layout_email);
        address = findViewById(R.id.text_input_layout_address);
        phone = findViewById(R.id.text_input_layout_phone);


    }
}
