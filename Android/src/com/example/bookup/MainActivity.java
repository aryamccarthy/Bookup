package com.example.bookup;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

public class MainActivity extends Activity {
	 EditText etResponse;
	 TextView tvIsConnected;
	//For reference on HTTP request: http://hmkcode.com/android-parsing-json-data/
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		// get reference to the views
        etResponse = (EditText) findViewById(R.id.etResponse);
        tvIsConnected = (TextView) findViewById(R.id.tvIsConnected);
 
        //For discovery page only, to be changed to getRecommendedBook?user=email
         new HttpAsyncTask().execute("http://ec2-54-187-70-205.us-west-2.compute.amazonaws.com/API/index.php/getRandomBook");
	}
	
	 public static String getRandomBook(String url){
	        InputStream inputStream = null;
	        String result = "";
	        try {
	 
	            // create HttpClient
	            HttpClient httpclient = new DefaultHttpClient();
	 
	            // make GET request to the given URL
	            HttpResponse httpResponse = httpclient.execute(new HttpGet(url));
	 
	            // receive response as inputStream
	            inputStream = httpResponse.getEntity().getContent();
	 
	            // convert inputstream to string
	            if(inputStream != null)
	                result = convertInputStreamToString(inputStream);
	            else
	                result = "Did not work!";
	 
	        } catch (Exception e) {
	            Log.d("InputStream", e.getLocalizedMessage());
	        }
	 
	        return result;
	    }
	 
	    private static String convertInputStreamToString(InputStream inputStream) throws IOException{
	        BufferedReader bufferedReader = new BufferedReader( new InputStreamReader(inputStream));
	        String line = "";
	        String result = "";
	        while((line = bufferedReader.readLine()) != null)
	            result += line;
	 
	        inputStream.close();
	        return result;
	 
	    }
	 
	    public boolean isConnected(){
	        ConnectivityManager connMgr = (ConnectivityManager) getSystemService(Activity.CONNECTIVITY_SERVICE);
	            NetworkInfo networkInfo = connMgr.getActiveNetworkInfo();
	            if (networkInfo != null && networkInfo.isConnected()) 
	                return true;
	            else
	                return false;   
	    }
	    
	    private class HttpAsyncTask extends AsyncTask<String, Void, String> {
	        @Override
	        protected String doInBackground(String... urls) {
	 
	            return getRandomBook(urls[0]);
	        }

	        @Override
	        protected void onPostExecute(String result) {
	        	Toast.makeText(getBaseContext(), "Received!", Toast.LENGTH_LONG).show();
	        	try {
					JSONObject json = new JSONObject(result);
					
					String str = "";
					
					JSONArray books = json.getJSONArray("Books");
					etResponse.setText(json.toString(1));

					for (int i = 0; i < books.length(); i++) {
					    JSONObject c = books.getJSONObject(i);
						//etResponse.setText(c.toString(1));

					    JSONObject volumeInfo = c.getJSONObject("volumeInfo");  
					    String title= volumeInfo.optString("title");
					   // String author= volumeInfo.optString("authors");
					    String description =volumeInfo.optString("description");
					    JSONObject imageLinks = volumeInfo.getJSONObject("imageLinks");
					    String thumbnailUrl= imageLinks.optString("thumbnail");

					 }
					
		        	//etResponse.setText(books.toString(1));

				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
	       }
	    }
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle action bar item clicks here. The action bar will
		// automatically handle clicks on the Home/Up button, so long
		// as you specify a parent activity in AndroidManifest.xml.
		int id = item.getItemId();
		if (id == R.id.action_settings) {
			return true;
		}
		return super.onOptionsItemSelected(item);
	}
}
