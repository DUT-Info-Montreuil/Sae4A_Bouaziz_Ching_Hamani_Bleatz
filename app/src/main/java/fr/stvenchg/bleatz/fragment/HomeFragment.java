package fr.stvenchg.bleatz.fragment;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import fr.stvenchg.bleatz.R;
import fr.stvenchg.bleatz.activity.AddAddressActivity;
import fr.stvenchg.bleatz.activity.MainActivity;
import fr.stvenchg.bleatz.api.ApiClient;
import fr.stvenchg.bleatz.api.ApiInterface;
import fr.stvenchg.bleatz.api.AuthenticationManager;
import fr.stvenchg.bleatz.api.account.AccountResponse;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link HomeFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class HomeFragment extends Fragment {
    private TextView bonjourTextView;
    private String firstname;
    private String address;
    private TextView addressTextView;

    private LinearLayout addAddressBar;

    private CardView burger_1, burger_2, burger_3;

    private static final int REQUEST_CODE = 100;

    public HomeFragment() {
        // Required empty public constructor
    }

    public static HomeFragment newInstance(String firstname, String address) {
        HomeFragment fragment = new HomeFragment();
        Bundle args = new Bundle();
        args.putString("firstname", firstname);
        args.putString("address", address);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            firstname = getArguments().getString("firstname");
            address = getArguments().getString("address");
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_home, container, false);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == REQUEST_CODE && resultCode == Activity.RESULT_OK) {
            if (data != null) {
                String address = data.getStringExtra("address");
                addressTextView.setText(address);
            }
        }
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        bonjourTextView = view.findViewById(R.id.fhome_textview_bonjour);
        addAddressBar = view.findViewById(R.id.fhome_linearlayout_addaddress);
        addressTextView = view.findViewById(R.id.fhome_textview_address);

        burger_1 = view.findViewById(R.id.burger_1);

        bonjourTextView.setText("Salut " + firstname + ",");
        addressTextView.setText(address);

        burger_1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
            }
        });


        addAddressBar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getActivity(), AddAddressActivity.class);
                startActivityForResult(intent, REQUEST_CODE);
            }
        });

    }


}