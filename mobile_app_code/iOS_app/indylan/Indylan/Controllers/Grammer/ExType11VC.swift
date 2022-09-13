//
//  ExType11VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

class ExType11VC: ExerciseController, UITextViewDelegate, AVAudioPlayerDelegate {
    
    @IBOutlet var scrView: BaseScrollView!
    
    @IBOutlet var contentView: UIView!
    
    @IBOutlet var lblQuestion: UILabel!
    
    @IBOutlet weak var btnNote: ThemeButton!
    @IBOutlet weak var btnAudio: UIButton!
    
    @IBOutlet weak var vwTextContainer: CardView!
    @IBOutlet var textViewAnswer: UITextView!
    
    @IBOutlet var lblPlaceHolder: UILabel!
        
    @IBOutlet var btnContinue: UIButton!
    
    @IBOutlet var textViewHeight: NSLayoutConstraint!
    
    @IBOutlet weak var btnShowAns : UIButton!
  
    var indicator : UIActivityIndicatorView!
    
    var audioPlayer: AVPlayer!
    
    var playerItem: AVPlayerItem!
    
    var urlString = ""
    
    var arrType11Questions = Array<JSON>()
    
    var questionIndex = 0
    
    var score = 0
    
    var attempts = 0

    var wrongAttempts = 0

    var isAlertDisplayed = false
    
    var option = ""
    
    var strBlank = ""

    var strOld = ""
    
    var isAlreadyChanged = false
    
    override func viewDidLoad() {
        
        super.viewDidLoad()

        audioPlayer = nil
        btnShowAns.setTitle("Show Correct Answer", for: .normal)
        self.title = selectedCategory

        lblPlaceHolder.font = Fonts.centuryGothic(ofType: .regular, withSize: 16)
        lblPlaceHolder.textColor = Colors.gray
        lblPlaceHolder.text = "typeAnswer".localized()
        lblPlaceHolder.backgroundColor = .clear

        if (UserDefaults.standard.object(forKey: "type11KeyboardAlert")) != nil {
            isAlertDisplayed = true
        }
        
        if #available(iOS 11.0, *) {
            textViewAnswer.smartQuotesType = .no
        }
        
        lblQuestion.textColor = Colors.black
        lblQuestion.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        textViewAnswer.textColor = Colors.black
        textViewAnswer.font = Fonts.centuryGothic(ofType: .bold, withSize: 18)
        textViewAnswer.backgroundColor = .clear
        
        vwTextContainer.layer.borderWidth = themeBorderWidth
        
        btnContinue.layer.borderColor = Colors.border.cgColor
        btnContinue.layer.borderWidth = 1
        btnContinue.layer.cornerRadius = 8
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.backgroundColor = Colors.white
        btnContinue.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnContinue.setTitle("matchWords".localized(), for: UIControlState.normal)
        view.bringSubview(toFront: btnContinue)
        
        let fixedWidth = textViewAnswer.frame.size.width
        textViewAnswer.sizeThatFits(CGSize(width: fixedWidth, height: CGFloat.greatestFiniteMagnitude))
        let newSize = textViewAnswer.sizeThatFits(CGSize(width: fixedWidth, height: CGFloat.greatestFiniteMagnitude))
        
        textViewHeight.constant = newSize.height
        
        btnNote.layer.cornerRadius = 4
        btnNote.tintColor = Colors.white
        
        self.updateQuestion()
    }

    // MARK: Functions

    func updateQuestion() {
        if audioPlayer != nil {
            indicator.stopAnimating()
            audioPlayer.pause()
        }
        
        self.view.isUserInteractionEnabled = true
        
        attempts = 0
        wrongAttempts = 0
        
        btnContinue.backgroundColor = Colors.white
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.setTitle("typeAnswer".localized(), for: .normal)
        btnContinue.isUserInteractionEnabled = false
        
        vwTextContainer.backgroundColor = UIColor.white
        vwTextContainer.layer.borderColor = Colors.red.cgColor
        
        textViewAnswer.text = ""
        lblPlaceHolder.isHidden = !textViewAnswer.text.isEmpty

        var strQuestion = arrType11Questions[questionIndex]["question"].stringValue
        let range = strQuestion.range(of: "...")

        if range != nil {
            option = arrType11Questions[questionIndex]["options"].stringValue
            
            var strReplace = ""
            
            for _ in option.indices {
                strReplace = strReplace + "_"
            }
            
            strBlank = strReplace
            
            strOld = strReplace
            
            strQuestion = strQuestion.replacingOccurrences(of: "...", with: strReplace)
        }
        
        lblQuestion.text = strQuestion
        
        if (arrType11Questions[questionIndex]["audio_file"].stringValue.isEmpty)
        {
            btnAudio.isHidden = true
        }
        else
        {
            btnAudio.isHidden = false
            urlString = arrType11Questions[questionIndex]["audio_file"].stringValue
        }
        
        if (arrType11Questions[questionIndex]["notes"].stringValue.isEmpty)
        {
            btnNote.isHidden = true
        }
        else
        {
            btnNote.isHidden = false
        }
    }
    

// MARK: UITextView Delegates
    
    func textViewDidBeginEditing(_ textView: UITextView) {
        
        if !(textView.textInputMode?.primaryLanguage?.hasPrefix("sv"))! && !isAlertDisplayed {
            isAlertDisplayed = true

            UserDefaults.standard.set( true, forKey: "type11KeyboardAlert")
            UserDefaults.standard.synchronize()

//            let alert = UIAlertController(title: "Alert", message: "Please select Swedish keyboard from settings.\r Go to Settings -> Keyboard -> Keyboards -> Add new Keyboard and select Swedish", preferredStyle: UIAlertControllerStyle.alert)
//            alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
//            self.present(alert, animated: true, completion: nil)
        }
        
        lblPlaceHolder.isHidden = true
    }
    
    func textViewDidChange(_ textView: UITextView) {
        
        lblPlaceHolder.isHidden = (!textView.text.isEmpty)

        let str = textView.text!
        
        if (!str.isEmpty && str[str.index(before: str.endIndex)] == "\n") {
            textView.text.remove(at: textView.text.index(before: textView.text.endIndex))
            self.view.endEditing(true)
            return
        }
        
        if (textView.text?.count)! > 0 {
            
            let fixedWidth = textView.frame.size.width
            textView.sizeThatFits(CGSize(width: fixedWidth, height: CGFloat.greatestFiniteMagnitude))
            let newSize = textView.sizeThatFits(CGSize(width: fixedWidth, height: CGFloat.greatestFiniteMagnitude))
            
            textViewHeight.constant = newSize.height

            attempts += 1
            let strWord = arrType11Questions[questionIndex]["options"].stringValue

            let start = strWord.index(strWord.startIndex, offsetBy: textView.text.count)
            
            if textView.text.lowercased() == String(strWord[..<start]).lowercased() {
                vwTextContainer.backgroundColor = Colors.green.withAlphaComponent(0.15)
                vwTextContainer.layer.borderColor = Colors.green.cgColor
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    self.vwTextContainer.animateBorderColor(toColor: Colors.red, duration: 0.5)
                    
                    UIView.animate(withDuration: 0.5, animations: { () -> Void in
                        self.vwTextContainer.backgroundColor = UIColor.white
                    }, completion: nil)
                }
            
                let strReplace = textView.text + String(strBlank.dropFirst())

                lblQuestion.text = lblQuestion.text?.replacingOccurrences(of: strOld, with: strReplace)
                
                let string              =  lblQuestion.text
                let range               = (string! as NSString).range(of: strReplace)
                let attributedString    = NSMutableAttributedString(string: string!)
                
                attributedString.addAttribute(NSAttributedStringKey.underlineStyle, value: NSNumber(value: 1), range: range)
                
                attributedString.addAttributes([
                    NSAttributedString.Key.foregroundColor: Colors.black,
                    NSAttributedString.Key.font: Fonts.centuryGothic(ofType: .bold, withSize: 14)
                ], range: (string! as NSString).range(of: string!))
                
                lblQuestion.attributedText = attributedString
                
                strOld = strReplace
                
                strBlank = String(strBlank.dropFirst())
            }
            else
            {
                TapticEngine.notification.feedback(.error)
                
                wrongAttempts += 1

                if wrongAttempts == 5 {
                    btnContinue.isUserInteractionEnabled = true
                }
                
                textView.text.remove(at: textView.text.index(before: textView.text.endIndex))
                
                vwTextContainer.backgroundColor = Colors.red.withAlphaComponent(0.15)
                vwTextContainer.layer.borderColor = Colors.red.cgColor
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    self.vwTextContainer.animateBorderColor(toColor: Colors.red, duration: 0.8)
                    
                    UIView.animate(withDuration: 0.5, animations: { () -> Void in
                        self.vwTextContainer.backgroundColor = UIColor.white
                    }, completion: nil)
                }
                
                UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnContinue.backgroundColor = Colors.white
                    self.btnContinue.setTitle("retry".localized(), for: .normal)
                    self.btnContinue.setTitleColor(Colors.red, for: .normal)
                }, completion: { _ in
                    
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        UIView.transition(with: self.btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                            
                            self.btnContinue.backgroundColor = Colors.white
                            
                            if self.wrongAttempts > 5 {
                                self.btnShowAns.isHidden = false
                                
                                if !self.isAlreadyChanged{
                                    self.btnShowAns.backgroundColor = Colors.white
                                    self.btnShowAns.setTitleColor(Colors.green, for: .normal)
                                    self.btnShowAns.setTitle("showCorrectAnswer".localized(), for: .normal)
                                }
                            } else {
                                self.btnShowAns.backgroundColor = Colors.white
                                self.btnShowAns.setTitleColor(Colors.green, for: .normal)
                                self.btnShowAns.setTitle("showCorrectAnswer".localized(), for: .normal)
                                
                                self.btnContinue.setTitleColor(Colors.black, for: .normal)
                                self.btnContinue.setTitle("typeAnswer".localized(), for: .normal)
                            }
                        })
                    }
                })
            }
            
            if textView.text.lowercased() == arrType11Questions[questionIndex]["options"].stringValue.lowercased() {
                self.view.endEditing(true)
                
                TapticEngine.notification.feedback(.success)
                
                if attempts == arrType11Questions[questionIndex]["options"].stringValue.count {
                    score += 1
                }
                
                vwTextContainer.animateBorderColor(toColor: Colors.green, duration: 0.3)
                
                UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                        self.vwTextContainer.backgroundColor = Colors.green.withAlphaComponent(0.15)
                        self.btnContinue.backgroundColor = Colors.green
                        self.btnContinue.setTitleColor(Colors.white, for: .normal)
                        self.btnContinue.setTitle("correct".localized(), for: .normal)
                }, completion: { _ in
                        
                    self.questionIndex += 1
                        
                        if (self.arrType11Questions.count > self.questionIndex)
                        {
                            UIView.animate(withDuration: 0.5, animations: {
                                let animation = CATransition()
                                animation.duration = 1.0
                                animation.startProgress = 0.0
                                animation.endProgress = 1.0
                                animation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseOut)
                                self.btnShowAns.isHidden = true
                                self.btnShowAns.setTitle("Show Correct Answer".localized(), for: .normal)
                                self.btnShowAns.backgroundColor = Colors.white
                                self.btnShowAns.setTitleColor(Colors.green, for: .normal)
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
                            objExComplete.totalQuestions = self.arrType11Questions.count
                            self.navigationController?.pushViewController(objExComplete, animated: true)
                        }
                })
            }
        }
    }
    
    func textView(_ textView: UITextView, shouldChangeTextIn range: NSRange, replacementText text: String) -> Bool {
        
        if text.isEmpty {
            return false
        }
        
        return true
    }
    
    func textViewShouldEndEditing(_ textView: UITextView) -> Bool {
        lblPlaceHolder.isHidden = (!textView.text.isEmpty)

        self.view.endEditing(true)
        return true
    }
    
    // MARK: Button Actions
    
    @IBAction func btnShowAnsTap(){
//        UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
        self.btnShowAns.titleLabel?.lineBreakMode = .byWordWrapping
        self.btnShowAns.titleLabel?.numberOfLines = 2
        self.btnShowAns.titleLabel?.textAlignment = .center
            self.btnShowAns.setTitle(self.arrType11Questions[self.questionIndex]["options"].stringValue, for: .normal)
        isAlreadyChanged = true
            self.btnShowAns.setTitleColor(Colors.white, for: .normal)
            self.btnShowAns.backgroundColor = Colors.green
//        }, completion: nil)

    }
    
    @IBAction func btnSeeAnsPressed(_ sender: UIButton) {
        UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnContinue.setTitle(self.arrType11Questions[self.questionIndex]["options"].stringValue, for: .normal)
            self.btnContinue.setTitleColor(Colors.white, for: .normal)
            self.btnContinue.backgroundColor = Colors.green
        }, completion: nil)
    }
    
    @IBAction func btnNoteAction(_ sender: ThemeButton) {
        let note = arrType11Questions[questionIndex]["notes"].stringValue
        guard !note.isEmpty else { return }
        Alert.showWith(message: note, completion: nil)
    }
    
    @IBAction func btnAudioAction(_ sender: UIButton) {
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
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        if audioPlayer != nil {
            audioPlayer.removeObserver(self, forKeyPath: "status")
        }
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        textViewAnswer.becomeFirstResponder()
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

}
