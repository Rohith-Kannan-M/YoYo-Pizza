package com.YellowMessenger.yoyo_pizza;

public class Messages {
    String message;
    boolean findWho;

    public Messages(String message, boolean findWho) {
        this.message = message;
        this.findWho = findWho;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public boolean isFindWho() {
        return findWho;
    }

    public void setFindWho(boolean findWho) {
        this.findWho = findWho;
    }
}
