package com.YellowMessenger.yoyo_pizza;

import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;

public interface ApiInterface {
    @FormUrlEncoded
    @POST("chatbot2.php")
    Call<String> getUserRegi(
            @Field("message_sent") String initmes,
            @Field("prev_ques") String pq,
            @Field("items") String items,
            @Field("name_in_order") String nio,
            @Field("delivery_addr") String deladr,
            @Field("phone") String phone
    );
}
