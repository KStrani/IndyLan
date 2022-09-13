package com.indylan.ui.home

import android.content.Context
import android.graphics.drawable.Drawable
import android.net.Uri
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.lifecycle.LifecycleOwner
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.load.DataSource
import com.bumptech.glide.load.engine.GlideException
import com.bumptech.glide.request.RequestListener
import com.bumptech.glide.request.target.Target
import com.indylan.common.extensions.goneView
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.ExerciseMode
import com.indylan.databinding.ItemExerciseModeBinding

class ExerciseModeAdapter(
    private val context: Context,
    private val lifecycleOwner: LifecycleOwner,
    private val callback: (ExerciseMode) -> Unit
) : ListAdapter<ExerciseMode, ExerciseFlagViewHolder>(ExerciseDiff) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ExerciseFlagViewHolder {
        val binding =
            ItemExerciseModeBinding.inflate(LayoutInflater.from(parent.context), parent, false)
                .apply {
                    root.layoutDirection = context.resources.configuration.layoutDirection
                    constraintLayoutExercise.setOnClickListener {
                        exerciseMode?.let {
                            callback.invoke(it)
                        }
                    }
                }
        return ExerciseFlagViewHolder(binding, lifecycleOwner)
    }

    override fun onBindViewHolder(holder: ExerciseFlagViewHolder, position: Int) {
        holder.bind(getItem(position))
    }
}

class ExerciseFlagViewHolder(
    private val binding: ItemExerciseModeBinding,
    private val lifecycleOwner: LifecycleOwner
) : RecyclerView.ViewHolder(binding.root) {

    fun bind(exerciseMode: ExerciseMode) {
        binding.exerciseMode = exerciseMode
        binding.lifecycleOwner = lifecycleOwner
        binding.executePendingBindings()
        GlideApp.with(binding.root.context)
            .load(Uri.parse("file:///android_asset/exercise_modes/${exerciseMode.id}.png"))
            .listener(object : RequestListener<Drawable> {
                override fun onLoadFailed(
                    e: GlideException?,
                    model: Any?,
                    target: Target<Drawable>?,
                    isFirstResource: Boolean
                ): Boolean {
                    binding.cardExercise.goneView()
                    return false
                }

                override fun onResourceReady(
                    resource: Drawable?,
                    model: Any?,
                    target: Target<Drawable>?,
                    dataSource: DataSource?,
                    isFirstResource: Boolean
                ): Boolean {
                    binding.progressBar.goneView()
                    return false
                }

            })
            .into(binding.imageViewExercise)
    }
}

object ExerciseDiff : DiffUtil.ItemCallback<ExerciseMode>() {
    override fun areItemsTheSame(oldItem: ExerciseMode, newItem: ExerciseMode): Boolean {
        return oldItem.id == newItem.id
    }

    override fun areContentsTheSame(oldItem: ExerciseMode, newItem: ExerciseMode): Boolean {
        return oldItem == newItem
    }
}