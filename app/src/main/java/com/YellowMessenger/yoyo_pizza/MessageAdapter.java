package com.YellowMessenger.yoyo_pizza;

import android.content.Context;
import android.os.Build;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.RequiresApi;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

public class MessageAdapter extends RecyclerView.Adapter<MessageAdapter.CustomViewHolder> {
    List<Messages> messages;
    Context context;

    public MessageAdapter(List<Messages> allresponse, Context context) {
        this.messages = allresponse;
        this.context = context;
    }

    @NonNull
    @Override
    public CustomViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        return new CustomViewHolder(LayoutInflater.from(parent.getContext()).inflate(viewType, parent, false));
    }

    @Override
    public int getItemViewType(int position) {
        if (messages.get(position).findWho) {
            return R.layout.customer_layout;
        }
        //}else{
        return R.layout.botside_layout;
        //}
    }

    @RequiresApi(api = Build.VERSION_CODES.N)
    @Override
    public void onBindViewHolder(@NonNull CustomViewHolder holder, int position) {
        holder.textView.setText(Html.fromHtml(messages.get(position).getMessage(), Html.FROM_HTML_MODE_COMPACT));
    }

    @Override
    public int getItemCount() {
        return messages.size();
    }

    public class CustomViewHolder extends RecyclerView.ViewHolder {
        TextView textView;

        public CustomViewHolder(@NonNull View itemView) {
            super(itemView);
            textView = itemView.findViewById(R.id.textMessage);
        }
    }
}
