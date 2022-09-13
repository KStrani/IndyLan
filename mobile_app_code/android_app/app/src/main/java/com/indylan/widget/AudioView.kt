package com.indylan.widget

import android.content.Context
import android.media.AudioAttributes
import android.media.MediaPlayer
import android.util.AttributeSet
import android.widget.Toast
import androidx.appcompat.widget.AppCompatImageView
import androidx.core.content.ContextCompat
import com.indylan.R
import timber.log.Timber

class AudioView @JvmOverloads constructor(context: Context, attr: AttributeSet? = null) :
    AppCompatImageView(context, attr),
    MediaPlayer.OnCompletionListener,
    MediaPlayer.OnPreparedListener,
    MediaPlayer.OnErrorListener {

    private var colorWhite = ContextCompat.getColor(context, android.R.color.white)
    private var colorPink = ContextCompat.getColor(context, R.color.colorPink)

    private var listener: Listener? = null

    private var isPlaying = false
    private var player: MediaPlayer? = MediaPlayer().apply {
        val attributes = AudioAttributes.Builder()
            .setContentType(AudioAttributes.CONTENT_TYPE_SPEECH)
            .build()
        setAudioAttributes(attributes)
        setOnCompletionListener(this@AudioView)
        setOnPreparedListener(this@AudioView)
        setOnErrorListener(this@AudioView)
    }

    fun showAudioView(isPlaying: Boolean) {
        if (isPlaying) {
            setBackgroundResource(R.drawable.bg_circle_pink)
            setColorFilter(colorWhite)
        } else {
            setBackgroundResource(R.drawable.bg_circle_pink_border)
            setColorFilter(colorPink)
        }
    }

    fun setListener(listener: Listener) {
        this.listener = listener
    }

    fun playAudio(audio: String?, callbackError: () -> (Unit)) {
        if (player != null && !audio.isNullOrEmpty()) {
            isPlaying = true
            try {
                player?.setDataSource(audio)
                player?.prepareAsync()
                showAudioView(true)
            } catch (e: Exception) {
                isPlaying = false
                e.printStackTrace()
                callbackError.invoke()
            }
        } else {
            callbackError.invoke()
        }
    }

    override fun onCompletion(mp: MediaPlayer?) {
        player?.reset()
        isPlaying = false
        showAudioView(false)
    }

    override fun onPrepared(mp: MediaPlayer?) {
        isPlaying = true
        mp?.start()
    }

    override fun onError(mp: MediaPlayer?, what: Int, extra: Int): Boolean {
        isPlaying = false
        Timber.e("Unable to play audio: $what")
        context?.let {
            Toast.makeText(it, "Unable to play audio: $what", Toast.LENGTH_SHORT).show()
        }
        return false
    }

    override fun onDetachedFromWindow() {
        player?.release()
        player = null
        super.onDetachedFromWindow()
    }

    interface Listener {
        fun onPlayStateChange(isPlaying: Boolean)
    }
}