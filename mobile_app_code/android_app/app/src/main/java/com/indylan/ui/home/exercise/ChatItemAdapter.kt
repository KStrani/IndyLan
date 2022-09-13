package com.indylan.ui.home.exercise

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.appcompat.widget.AppCompatTextView
import androidx.core.view.isVisible
import androidx.recyclerview.widget.RecyclerView
import com.indylan.R
import com.indylan.data.model.DialogList
import com.indylan.widget.AudioView

class ChatItemAdapter(
    private val callbackError: () -> (Unit)
) : RecyclerView.Adapter<RecyclerView.ViewHolder>() {

    private var dialogList: MutableList<DialogList> = mutableListOf()

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyclerView.ViewHolder {
        return when (viewType) {
            1 -> ReceiverViewHolder(
                LayoutInflater.from(parent.context)
                    .inflate(R.layout.item_chat_receiver, parent, false)
            )
            else -> SenderViewHolder(
                LayoutInflater.from(parent.context)
                    .inflate(R.layout.item_chat_sender, parent, false)
            )
        }
    }

    override fun getItemCount(): Int = dialogList.size

    override fun getItemViewType(position: Int): Int = dialogList[position].parseSpeaker()

    override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
        if (holder is SenderViewHolder) {
            holder.bindData(dialogList[position], callbackError)
        } else if (holder is ReceiverViewHolder) {
            holder.bindData(dialogList[position], callbackError)
        }
    }

    fun submitItems(items: List<DialogList>?) {
        dialogList = items?.toMutableList() ?: mutableListOf()
        notifyDataSetChanged()
    }

    fun addNewItem(item: DialogList) {
        dialogList.add(item)
        notifyItemInserted(itemCount)
    }

    inner class SenderViewHolder(view: View) : RecyclerView.ViewHolder(view) {

        private val textView = view.findViewById<AppCompatTextView>(R.id.textView)
        private val audioView = view.findViewById<AudioView>(R.id.audioView)

        fun bindData(dialogList: DialogList, callbackError: () -> (Unit)) {
            textView.text = dialogList.fixPhrase()
            textView.tag = dialogList
            audioView.isVisible =
                dialogList.isAudioAvailable == "1" && !dialogList.audio.isNullOrEmpty()
            audioView.setOnClickListener {
                audioView.playAudio(dialogList.audio, callbackError)
            }
        }
    }

    inner class ReceiverViewHolder(view: View) : RecyclerView.ViewHolder(view) {

        private val textView = view.findViewById<AppCompatTextView>(R.id.textView)
        private val audioView = view.findViewById<AudioView>(R.id.audioView)

        fun bindData(dialogList: DialogList, callbackError: () -> (Unit)) {
            textView.text = dialogList.fixPhrase()
            textView.tag = dialogList
            audioView.isVisible =
                dialogList.isAudioAvailable == "1" && !dialogList.audio.isNullOrEmpty()
            audioView.setOnClickListener {
                audioView.playAudio(dialogList.audio, callbackError)
            }
        }
    }
}