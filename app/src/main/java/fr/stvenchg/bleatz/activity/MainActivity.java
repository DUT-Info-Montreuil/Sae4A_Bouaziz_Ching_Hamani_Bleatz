package fr.stvenchg.bleatz.activity;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.widget.LinearLayout;
import android.view.View;
import android.widget.Toast;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import fr.stvenchg.bleatz.EmailVerificationCallback;
import fr.stvenchg.bleatz.fragment.AccountFragment;

import fr.stvenchg.bleatz.fragment.BurgerMenuFragment;
import fr.stvenchg.bleatz.fragment.HomeFragment;
import fr.stvenchg.bleatz.R;
import fr.stvenchg.bleatz.fragment.RestaurantLocationFragment;
import fr.stvenchg.bleatz.api.ApiClient;
import fr.stvenchg.bleatz.api.ApiInterface;
import fr.stvenchg.bleatz.api.AuthenticationManager;
import fr.stvenchg.bleatz.api.account.AccountResponse;
import fr.stvenchg.bleatz.databinding.ActivityMainBinding;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity {

    ActivityMainBinding binding;
    private AuthenticationManager authenticationManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);




        binding = ActivityMainBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        authenticationManager = new AuthenticationManager(this);
        fetchUserDetails(new UserDetailsListener() {
            @Override
            public void onUserDetailsFetched(AccountResponse accountResponse) {
                // Les informations de l'utilisateur ont été récupérées avec succès
                // Utiliser les informations de l'utilisateur ici
            }

            @Override
            public void onUserDetailsFetchFailed(Throwable t) {
                // La récupération des informations de l'utilisateur a échoué
                // Traiter l'erreur ici
            }
        });

        binding.navItemHome.setOnClickListener(v -> {
            String firstname = authenticationManager.getFirstname();
            String address = authenticationManager.getAddress();

            if (firstname != null) {
                replaceFragment(HomeFragment.newInstance(firstname, address), R.id.nav_item_home);
            } else {
                replaceFragment(new HomeFragment(), R.id.nav_item_home);
            }
        });

        binding.navItemBurgermenu.setOnClickListener(v -> replaceFragment(new BurgerMenuFragment(), R.id.nav_item_burgermenu));
        binding.navItemLocation.setOnClickListener(v -> replaceFragment(new RestaurantLocationFragment(), R.id.nav_item_location));

        binding.navItemAccount.setOnClickListener(v -> {
            String firstname = authenticationManager.getFirstname();
            String lastname = authenticationManager.getLastname();
            String role = authenticationManager.getRole();

            if (firstname != null) {
                replaceFragment(AccountFragment.newInstance(firstname, lastname, role), R.id.nav_item_account);
            } else {
                replaceFragment(new AccountFragment(), R.id.nav_item_account);
            }
        });


        // Vérification de l'email et du numéro de téléphone
        if (authenticationManager.getEmail() != null && authenticationManager.getAccessToken() != null && authenticationManager.getRefreshToken() != null)  {
            checkEmailVerified(new EmailVerificationCallback() {
                @Override
                public void onEmailVerificationDone(boolean isEmailVerified) {
                    if (isEmailVerified) {
                        new Handler().postDelayed(new Runnable() {
                            @Override
                            public void run() {
                                checkPhoneVerified();
                            }
                        }, 1000);
                    }
                }
            });
        }
    }


    private void replaceFragment(Fragment fragment, int selectedIconId) {
        updateSelectedIcon(selectedIconId);

        FragmentManager fragmentManager = getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction();
        fragmentTransaction.replace(R.id.main_framelayout, fragment);
        fragmentTransaction.commit();
    }
    public void updateSelectedIcon(int selectedIconId) {
        int selectedColor = getResources().getColor(R.color.orange_300);
        int defaultColor = getResources().getColor(R.color.grey_700);

        binding.navItemHome.setColorFilter(selectedIconId == R.id.nav_item_home ? selectedColor : defaultColor);
        binding.navItemBurgermenu.setColorFilter(selectedIconId == R.id.nav_item_burgermenu ? selectedColor : defaultColor);
        binding.navItemLocation.setColorFilter(selectedIconId == R.id.nav_item_location ? selectedColor : defaultColor);
        binding.navItemAccount.setColorFilter(selectedIconId == R.id.nav_item_account ? selectedColor : defaultColor);
    }
    private void checkEmailVerified(EmailVerificationCallback callback) {
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<AccountResponse> call = apiInterface.getAccount("Bearer " + authenticationManager.getAccessToken());

        call.enqueue(new Callback<AccountResponse>() {
            @Override
            public void onResponse(Call<AccountResponse> call, Response<AccountResponse> response) {
                if (response.isSuccessful()) {
                    AccountResponse accountResponse = response.body();
                    if (accountResponse != null && accountResponse.isSuccess()) {
                        if (!accountResponse.isEmailVerified()) {
                            redirectToAskEmailConfirmActivity();
                            callback.onEmailVerificationDone(false);
                        } else {
                            callback.onEmailVerificationDone(true);
                        }
                    }
                }
            }

            @Override
            public void onFailure(Call<AccountResponse> call, Throwable t) {
                // Gérer l'échec de la requête
            }
        });
    }
    public void openCartActivity(View view) {
        Intent intent = new Intent(this, CartActivity.class);
        startActivity(intent);
    }

    private void checkPhoneVerified() {
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<AccountResponse> call = apiInterface.getAccount("Bearer " + authenticationManager.getAccessToken());
        call.enqueue(new Callback<AccountResponse>() {
            @Override
            public void onResponse(Call<AccountResponse> call, Response<AccountResponse> response) {
                if (response.isSuccessful()) {
                    AccountResponse accountResponse = response.body();
                    if (accountResponse != null && accountResponse.isSuccess()) {
                        if (!accountResponse.isPhoneVerified() || accountResponse.getPhone() == null) {
                            redirectToAddPhoneActivity();
                        }
                    }
                }
            }

            @Override
            public void onFailure(Call<AccountResponse> call, Throwable t) {
                // Gérer l'échec de la requête
            }
        });
    }
    private void redirectToAskEmailConfirmActivity() {
        Intent intent = new Intent(MainActivity.this, AskEmailConfirmActivity.class);
        startActivity(intent);
        overridePendingTransition(R.anim.zoom_in, R.anim.zoom_out);
        finishAffinity();
    }
    private void redirectToAddPhoneActivity() {
        Intent intent = new Intent(MainActivity.this, AddPhoneActivity.class);
        startActivity(intent);
        overridePendingTransition(R.anim.zoom_in, R.anim.zoom_out);
        finishAffinity();
    }

    public interface UserDetailsListener {
        void onUserDetailsFetched(AccountResponse accountResponse);
        void onUserDetailsFetchFailed(Throwable t);
    }


    private void fetchUserDetails(UserDetailsListener listener) {
        AuthenticationManager authenticationManager = new AuthenticationManager(this);
        String accessToken = authenticationManager.getAccessToken();

        if (accessToken != null) {
            ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
            Call<AccountResponse> call = apiInterface.getAccount("Bearer " + accessToken);

            call.enqueue(new Callback<AccountResponse>() {
                @Override
                public void onResponse(Call<AccountResponse> call, Response<AccountResponse> response) {
                    if (response.isSuccessful()) {
                        AccountResponse accountResponse = response.body();
                        if (accountResponse != null && accountResponse.isSuccess()) {
                            authenticationManager.saveUserDetails(
                                    accountResponse.getUserId(),
                                    accountResponse.getFirstname(),
                                    accountResponse.getLastname(),
                                    accountResponse.getEmail(),
                                    accountResponse.getPhone(),
                                    accountResponse.getCreationDate(),
                                    accountResponse.getAddress(),
                                    accountResponse.getRole()
                            );

                            String firstname = accountResponse.getFirstname();
                            String address = accountResponse.getAddress();

                            HomeFragment homeFragment = HomeFragment.newInstance(firstname, address);
                            replaceFragment(homeFragment, R.id.nav_item_home);

                            System.out.println(accountResponse.getUserId());
                            System.out.println(accountResponse.getFirstname());
                            System.out.println(accountResponse.getLastname());
                            listener.onUserDetailsFetched(accountResponse); // Appel de la méthode de l'interface avec les informations de l'utilisateur
                        }
                    }
                }

                @Override
                public void onFailure(Call<AccountResponse> call, Throwable t) {
                    listener.onUserDetailsFetchFailed(t); // Appel de la méthode de l'interface avec l'erreur
                }
            });
        }
    }
}

