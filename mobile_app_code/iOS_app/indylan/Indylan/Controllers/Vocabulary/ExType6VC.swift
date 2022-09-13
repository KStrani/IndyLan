//
//  ExType6VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

class ExType6VC: ExerciseController, AVAudioPlayerDelegate, UIScrollViewDelegate {

    //MARK: DECLARATION
    
    @IBOutlet var scrView: BaseScrollView!
    
    @IBOutlet var viewOption1: UIView!
    
    @IBOutlet var imgViewOption1: UIImageView!
    
    @IBOutlet var btnAudio: UIButton!
    
    @IBOutlet var btnOption1: UIButton!
    
    @IBOutlet weak var vwQuestion: CardView!
    @IBOutlet var lblQuestion: UILabel!
    
    @IBOutlet var viewOption2: UIView!
    
    @IBOutlet var imgViewOption2: UIImageView!
    
    @IBOutlet var btnOption2: UIButton!
    
    @IBOutlet var btnChoosePicture: UIButton!
    
    var indicator : UIActivityIndicatorView!
    
    var audioPlayer: AVPlayer!
    
    var playerItem: AVPlayerItem!
    
    var arrType6Questions = Array<JSON>()
    
    var urlString = "" as String
    
    var questionIndex = 0
    
    var score = 0
    
    var attempts = 0
    
    override func viewDidLoad() {
       
        super.viewDidLoad()

        self.title = selectedCategory

        self.automaticallyAdjustsScrollViewInsets =  false

        viewOption1.layer.cornerRadius = 8
        viewOption2.layer.cornerRadius = 8
        
        viewOption1.clipsToBounds = true
        viewOption2.clipsToBounds = true
        
        vwQuestion.layer.borderWidth = themeBorderWidth
        vwQuestion.layer.borderColor = Colors.red.cgColor
        
        lblQuestion.textColor = Colors.black
        lblQuestion.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        btnChoosePicture.layer.borderWidth = 1
        btnChoosePicture.layer.borderColor = Colors.border.cgColor
        btnChoosePicture.layer.cornerRadius = 8
        btnChoosePicture.setTitleColor(Colors.black, for: .normal)
        btnChoosePicture.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnChoosePicture.backgroundColor = Colors.white
        view.bringSubview(toFront: btnChoosePicture)
        
        self.updateQuestion()
    }

//MARK: FUNCTIONS
    
    func updateQuestion() {
        
        if audioPlayer != nil {
            indicator.stopAnimating()

            audioPlayer.pause()
        }
        self.view.isUserInteractionEnabled = true
        
        scrView.setContentOffset(CGPoint.zero, animated: true)
        
        attempts = 0
        
        self.clearBackgroundColor()
        
        if (arrType6Questions[questionIndex]["audio_file"].stringValue.isEmpty)
        {
            btnAudio.isHidden = true
        }
        else
        {
            btnAudio.isHidden = false
            urlString = arrType6Questions[questionIndex]["audio_file"].stringValue
        }
        
        imgViewOption1.setImage(withURL: arrType6Questions[questionIndex]["option"][0]["image_path"].stringValue.addingPercentEncoding(withAllowedCharacters: .urlQueryAllowed)!)
        
        imgViewOption2.setImage(withURL: arrType6Questions[questionIndex]["option"][1]["image_path"].stringValue.addingPercentEncoding(withAllowedCharacters: .urlQueryAllowed)!)
        
        btnChoosePicture.setTitleColor(Colors.black, for: .normal)
        btnChoosePicture.backgroundColor = Colors.white
        btnChoosePicture.setTitle("choosePicture".localized(), for: .normal)
        
        lblQuestion.text = arrType6Questions[questionIndex]["word"].stringValue
    }
    
    func wrongImageSelected() {
        
        TapticEngine.notification.feedback(.error)
        
        self.view.isUserInteractionEnabled = false
        
        UIView.transition(with: btnChoosePicture, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnChoosePicture.backgroundColor = Colors.white
            self.btnChoosePicture.setTitleColor(Colors.red, for: .normal)
            self.btnChoosePicture.setTitle("retry".localized(), for: .normal)
        }, completion: nil)
        
        DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
            UIView.animate(withDuration: 0.3, animations: { () -> Void in
                self.clearBackgroundColor()
            }, completion: nil)
            
            UIView.transition(with: self.btnChoosePicture, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.btnChoosePicture.backgroundColor = Colors.white
                self.btnChoosePicture.setTitleColor(Colors.black, for: .normal)
                self.btnChoosePicture.setTitle("choosePicture".localized(), for: .normal)
            }, completion: { _ in
                self.view.isUserInteractionEnabled = true
            })
        }
    }
    
    func rightImageSelected()
    {
        TapticEngine.notification.feedback(.success)
        
        if attempts == 1 {
            score += 1
        }
        
        UIView.transition(with: btnChoosePicture, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnChoosePicture.backgroundColor = Colors.green
            self.btnChoosePicture.setTitleColor(Colors.white, for: .normal)
            self.btnChoosePicture.setTitle("correct".localized(), for: .normal)
        }, completion: { _ in
                
                self.questionIndex += 1
                
                self.view.isUserInteractionEnabled = true
                self.clearBackgroundColor()
                
                if (self.arrType6Questions.count > self.questionIndex)
                {
                    UIView.animate(withDuration: 0.5, animations: {
                        let animation = CATransition()
                        animation.duration = 1.0
                        animation.startProgress = 0.0
                        animation.endProgress = 1.0
                        animation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseOut)
                        animation.type = "pageCurl"
                        animation.subtype = "fromBottom"
                        animation.isRemovedOnCompletion = false
                        animation.fillMode = "extended"
                        self.view.layer.add(animation, forKey: "pageFlipAnimation")
                    })
                    self.updateQuestion()
                }
                else
                {
                    let objExComplete = UIStoryboard.exercise.instantiateViewController(withClass: CongratsVC.self)!
                    objExComplete.score = self.score
                    objExComplete.totalQuestions = self.arrType6Questions.count
                    self.navigationController?.pushViewController(objExComplete, animated: true)
                }
        })
        
    }
    
    func clearBackgroundColor()
    {
        btnOption1.backgroundColor = UIColor.clear
        btnOption2.backgroundColor = UIColor.clear
    }
    
// MARK: BUTTON ACTIONS
    
    @IBAction func btnOption1Clicked(_ sender: Any)
    {
        attempts += 1
        if arrType6Questions[questionIndex]["option"][0]["is_correct"].intValue == 1
        {
            btnOption1.backgroundColor = Colors.green
            btnOption1.alpha = 0.15
            rightImageSelected()
        }
        else
        {
            btnOption1.backgroundColor = Colors.red
            btnOption1.alpha = 0.15
            wrongImageSelected()
            
            viewOption1.shake()
        }
    }
    
    
    @IBAction func btnOption2Clicked(_ sender: Any)
    {
        attempts += 1
        if arrType6Questions[questionIndex]["option"][1]["is_correct"].intValue == 1
        {
            btnOption2.backgroundColor = Colors.green
            btnOption2.alpha = 0.15
            rightImageSelected()
        }
        else
        {
            btnOption2.backgroundColor = Colors.red
            btnOption2.alpha = 0.15
            wrongImageSelected()
            
            viewOption2.shake()
        }
    }
    
    @IBAction func btnAudioPressed(_ sender: Any) {
        let url =  URL(string: urlString as String)
        
        if (url != nil)
        {
            if audioPlayer != nil
            {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }
            playerItem = AVPlayerItem(url: url!)

            indicator = UIActivityIndicatorView(activityIndicatorStyle: .white)
            indicator.center = btnAudio.center
            viewOption1.addSubview(indicator)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
            
            audioPlayer = AVPlayer(playerItem:playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            audioPlayer.play()
        }
        else
        {
            SnackBar.show("Audio error")
        }
        
    }
    
    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?)
    {
        if keyPath == "status"
        {
            indicator.stopAnimating()
            btnAudio.isUserInteractionEnabled = true
        }
    }
    
    @IBAction func btnChoosePictureClicked(_ sender: Any) {
        
    }

    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        if audioPlayer != nil {
            audioPlayer.removeObserver(self, forKeyPath: "status")
        }
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
