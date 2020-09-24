package com.YellowMessenger.yoyo_pizza;

import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.inputmethod.EditorInfo;
import android.widget.EditText;
import android.widget.TextView;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity {

    EditText userMessage;
    RecyclerView recyclerView;
    MessageAdapter messageAdapter;
    List<Messages> messagesList;
    String result = "", botmes = "", prevq = "", itms = "", namio = "", delad = "", phone = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        userMessage = findViewById(R.id.userInput);
        recyclerView = findViewById(R.id.conversation);
        messagesList = new ArrayList<>();
        //initialMessage();
        //messageResponseMethod("");
        //Log.d("yes","here =>"+init);
        //Log.d("init",init);
        messageResponseMethod("");
//        Messages responseMessage2 = new Messages("Welcome to YoYo Pizza!, Choose from our varieties, pizza, burger, cool drinks", false);
//        messagesList.add(responseMessage2);
        messageAdapter = new MessageAdapter(messagesList, this);
        recyclerView.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false));
        recyclerView.setAdapter(messageAdapter);

        userMessage.setOnEditorActionListener(new TextView.OnEditorActionListener() {
            @Override
            public boolean onEditorAction(TextView textView, int i, KeyEvent keyEvent) {
                if (i == EditorInfo.IME_ACTION_SEND) {
                    String sending_message = userMessage.getText().toString().toLowerCase();
                    userMessage.setText("");
                    Messages responseMessage = new Messages(sending_message, true);
                    messagesList.add(responseMessage);
                    messageResponseMethod(sending_message);
                    Log.d("botmes", botmes);
//                    Messages responseMessage2 = new Messages(result, false);
//                    messagesList.add(responseMessage2);
                    Log.d("details", sending_message + " ** " + result);
                    //Log.d("","")
                    if (!isLastVisible()) {
                        recyclerView.smoothScrollToPosition(messageAdapter.getItemCount());
                    }
                }
                return false;
            }
        });
    }

    public String messageResponseMethod(String s) {
        //final String[] p = {""};
        ApiInterface api = ApiClient.getApiClinet().create(ApiInterface.class);
        Call<String> call = api.getUserRegi(s, prevq, itms, namio, delad, phone);
        call.enqueue(new Callback<String>() {
            @Override
            public void onResponse(Call<String> call, Response<String> response) {
                if (response.isSuccessful()) {
                    if (response.body() != null) {
                        Log.i("l-onSuccess", response.body());
                        String jsonresponse = response.body();
                        try {
                            JSONObject jsonObject = new JSONObject(jsonresponse);
                            Log.i("logres and jsonobject", "" + jsonresponse + "****" + jsonObject);
                            prevq = jsonObject.getString("prev_ques");
                            itms = jsonObject.getString("items");
                            namio = jsonObject.getString("name_in_order");
                            delad = jsonObject.getString("delivery_addr");
                            phone = jsonObject.getString("phone");
                            Messages responseMessage2 = new Messages(jsonObject.getString("message"), false);
                            messagesList.add(responseMessage2);
                            messageAdapter.notifyDataSetChanged();
                            //p[0] = jsonObject.getString("message");
                            //Log.d("result",p[0]);
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    } else {
                        Log.i("l-onEmptyResponse", "Returned empty response");//Toast.makeText(getContext(),"Nothing returned",Toast.LENGTH_LONG).show();
                    }
                }
            }

            @Override
            public void onFailure(Call<String> call, Throwable t) {
                Log.d("onFailure", call + " \n " + t);
            }
        });

        return null;
    }

    boolean isLastVisible() {
        LinearLayoutManager layoutManager = ((LinearLayoutManager) recyclerView.getLayoutManager());
        int pos = layoutManager.findLastCompletelyVisibleItemPosition();
        int numItems = recyclerView.getAdapter().getItemCount();
        return (pos >= numItems);
    }

    @Override
    public void onBackPressed() {
        openAlert();
    }

    private void openAlert() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage("Your chat history will be cleared after exit. Click Ok to exit");

        builder.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                //perform any action
                finish();
                System.exit(0);
            }
        });

        builder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                //perform any action
                dialog.cancel();
            }
        });

        AlertDialog alert11 = builder.create();
        alert11.show();
    }
}



