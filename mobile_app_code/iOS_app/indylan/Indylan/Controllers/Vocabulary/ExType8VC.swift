//
//  ExType8VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

class ExType8VC: ExerciseController, AVAudioPlayerDelegate {
    
    //MARK: DECLARATION
    
    @IBOutlet var scrView: BaseScrollView!

    @IBOutlet var imgViewQuestion: UIImageView!
    
    @IBOutlet var btnAudio: UIButton!
    
    @IBOutlet var lblQuestion: UILabel!
    
    @IBOutlet weak var lblTranslation: UILabel!
    @IBOutlet weak var btnSeeTranslation: BaseButton!
    
    @IBOutlet var btnContinue: BaseButton!
    
    var indicator : UIActivityIndicatorView!
    
    var audioPlayer: AVPlayer!
    
    var playerItem: AVPlayerItem!
    
    var arrType8Questions = Array<JSON>()
    
    var urlString = ""
    
    var questionIndex = 0

    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.title = selectedCategory.uppercased()

        self.automaticallyAdjustsScrollViewInsets = false

        audioPlayer = nil
        
        imgViewQuestion.layer.cornerRadius = 8
        
        lblQuestion.textColor = Colors.black
        lblQuestion.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        lblTranslation.isHidden = true
        lblTranslation.textColor = Colors.black
        lblTranslation.font = Fonts.centuryGothic(ofType: .regular, withSize: 14)
        
        btnSeeTranslation.layer.borderWidth = themeBorderWidth
        btnSeeTranslation.layer.borderColor = Colors.red.cgColor
        btnSeeTranslation.titleLabel?.font = Fonts.centuryGothic(ofType: .regular, withSize: 14)
        btnSeeTranslation.setTitle("seeTranslation".localized(), for: .normal)
        btnSeeTranslation.setTitleColor(Colors.black, for: .normal)
        
        btnContinue.layer.borderColor = Colors.border.cgColor
        btnContinue.layer.borderWidth = 1
        btnContinue.layer.cornerRadius = 8
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.backgroundColor = Colors.white
        view.bringSubview(toFront: btnContinue)
        
        if arrType8Questions.count > 0 {
            self.updateQuestion()
        } else {
            SnackBar.show("Some unexpected error has occured")
            self.navigationController?.popViewController(animated: true)
        }
    }
    
    //MARK: FUNCTIONS
    
    func updateQuestion() {
        
        if audioPlayer != nil {
            indicator.stopAnimating()
            audioPlayer.pause()
        }
        
        scrView.setContentOffset(.zero, animated: true)

        self.view.isUserInteractionEnabled = true
        
        lblTranslation.text = ""
        lblTranslation.isHidden = true
        
        self.btnContinue.setTitle("tapContinue".localized(), for: .normal)
        
        UIView.transition(with: btnSeeTranslation, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnSeeTranslation.setTitle("seeTranslation".localized(), for: .normal)
        }, completion: { _ in
            self.btnSeeTranslation.isUserInteractionEnabled = true
        })

        imgViewQuestion.setImage(withURL: arrType8Questions[questionIndex]["image_path"].stringValue)
        
        lblQuestion.text = arrType8Questions[questionIndex]["word"].stringValue
        
        if (arrType8Questions[questionIndex]["audio_file"].stringValue.isEmpty)
        {
            btnAudio.isHidden = true
        }
        else
        {
            btnAudio.isHidden = false
            urlString = arrType8Questions[questionIndex]["audio_file"].stringValue.addingPercentEncoding(withAllowedCharacters: NSCharacterSet.urlFragmentAllowed) ?? ""
        }
    }

    //MARK: BUTTON ACTIONS
    
    @IBAction func btnSeeTranslationClicked(_ sender: Any) {
        btnSeeTranslation.isUserInteractionEnabled = false
        lblTranslation.isHidden = false
                
        let translation = self.arrType8Questions[self.questionIndex]["option"].stringValue
        
        UIView.transition(with: btnSeeTranslation, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnSeeTranslation.setTitle("", for: .normal)
        }, completion: { _ in
            UIView.transition(with: self.lblTranslation, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.lblTranslation.text = translation
            }, completion: nil)
        })

        UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnContinue.setTitle("next".localized(), for: .normal)
        }, completion: nil)
    }
    
    @IBAction func btnAudioClicked(_ sender: UIButton) {
        
        let url =  URL(string: urlString as String)
        
        if (url != nil) {
            
            if audioPlayer != nil {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }
            
            playerItem = AVPlayerItem(url: url!)

            indicator = UIActivityIndicatorView(activityIndicatorStyle: .white)
            indicator.center = sender.center
            sender.superview?.addSubview(indicator)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
            
            audioPlayer = AVPlayer(playerItem: playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            audioPlayer.play()
            
        } else {
            SnackBar.show("Audio error")
        }
    }
    
    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
        
        if keyPath == "status" {
            indicator.stopAnimating()
            btnAudio.isUserInteractionEnabled = true
        }
    }

    @IBAction func btnContinueClicked(_ sender: Any) {
        questionIndex += 1
        
        if arrType8Questions.count > questionIndex {
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
            objExComplete.totalQuestions = self.arrType8Questions.count
            self.navigationController?.pushViewController(objExComplete, animated: true)
        }
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
