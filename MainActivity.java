package com.example.akhil.audio_p;

import android.app.DownloadManager;
import android.media.MediaPlayer;
import android.media.MediaRecorder;
import android.os.AsyncTask;
import android.os.Environment;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Base64;
import android.view.View;
import android.widget.TextView;


import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;

import java.util.HashMap;
import java.util.Map;



public class MainActivity extends AppCompatActivity {


    private MediaPlayer mediapayer;
    private MediaRecorder recorder;
    private String encoded_string,OUTPUT_FILE;
    TextView Status;



    private static String serverAddress= "http://192.168.1.103/tutorial3/connection.php";
    private static String audio_name = "audio_recorder.3gpp";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Status = (TextView) findViewById(R.id.status);
        OUTPUT_FILE= Environment.getExternalStorageDirectory()+ "/audio_recorder.3gpp";

    }

    public void buttonTapped(View view){
        switch(view.getId()){
            case R.id.startBtn:
                try{
                    beginRecording();
                    Status.setText("Recording");
                }
                catch (Exception e)
                {
                    e.printStackTrace();
                }
                break;

            case R.id.stopBtn:
                try{
                    stopRecording();
                    Status.setText("Stopped");
                }
                catch (Exception e)
                {
                    e.printStackTrace();
                }
                break;
            case R.id.startPlayBtn:
                try{
                    beginPlayback();
                    Status.setText("Playing");
                }
                catch (Exception e)
                {
                    e.printStackTrace();
                }
                break;

            case R.id.stopPlayBtn:
                try{
                    stopPlayback();
                    Status.setText("Stopped");
                }
                catch (Exception e)
                {
                    e.printStackTrace();
                }
                break;
            case R.id.sendBtn2:
                try{
                    //sendFile();
                    new Encode_audio().execute();
                    Status.setText("Sending");
                }
                catch (Exception e)
                {
                    e.printStackTrace();
                }
                break;
        }

    }

   private void beginRecording() throws IOException {
        ditchMediaRecorder();
        File outFile = new File(OUTPUT_FILE);
        if (outFile.exists())
        {
            outFile.delete();
        }

        recorder = new MediaRecorder();
        recorder.setAudioSource(MediaRecorder.AudioSource.MIC);
        recorder.setOutputFormat(MediaRecorder.OutputFormat.THREE_GPP);
        recorder.setAudioEncoder(MediaRecorder.AudioEncoder.AMR_WB);
        recorder.setOutputFile(OUTPUT_FILE);
        recorder.prepare();
        recorder.start();
    }

    private void stopRecording()
    {
        if(recorder!=null)
            recorder.stop();
    }

    private void beginPlayback() throws IOException {
        ditchMediaPlayer();
        mediapayer = new MediaPlayer();
        mediapayer.setDataSource(OUTPUT_FILE);
        mediapayer.prepare();
        mediapayer.start();

    }

    private void ditchMediaPlayer() {
        if(mediapayer != null)
        {
            try {
                mediapayer.release();
            }catch (Exception e)
            {
                e.printStackTrace();
            }
        }
    }


    private void stopPlayback()
    {
        if(mediapayer!=null)
            mediapayer.stop();
    }

    private void ditchMediaRecorder() {

        if(recorder!=null)
        {
            recorder.release();
        }
    }


    private class Encode_audio extends AsyncTask<Void, Void, Void> {
        @Override

        protected Void doInBackground(Void... voids) {

            System.out.println("Entered do in background");
            FileInputStream fis= null;
            try {
                File f = new File(Environment.getExternalStorageDirectory()+"/"+audio_name);
                if(f.isFile()) {
                    System.out.println("FIle exists, opened successfully");
                    fis = new FileInputStream(f);
                }
            } catch (FileNotFoundException e) {
                System.out.println("File does not exist");
                e.printStackTrace();

            } catch (IOException e) {
                e.printStackTrace();
            }
            ByteArrayOutputStream bos = new ByteArrayOutputStream();


            byte[] array = new byte[1024];
            try {
                for(int readNum;(readNum=fis.read(array))!=-1;){
                    bos.write(array,0,readNum);
                }
            } catch (IOException e) {
                e.printStackTrace();
            }
            byte[] bytes = bos.toByteArray();
            encoded_string = Base64.encodeToString(bytes, 0);
            System.out.println("Encoded Audio = "+ encoded_string);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            makeRequest2();
        }
    }

    private void makeRequest2() {
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        StringRequest request = new StringRequest( Request.Method.POST, serverAddress,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                HashMap<String,String> map = new HashMap<>();
                map.put("encoded_string",encoded_string);
                System.out.println("Encoded String = "+encoded_string);
                map.put("image_name",audio_name);

                return map;
            }
        };
        requestQueue.add(request);

    }




}
